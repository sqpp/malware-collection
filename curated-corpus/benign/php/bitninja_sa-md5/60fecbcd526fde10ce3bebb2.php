<?php 
// Get Project path
define('_PATH', dirname(__FILE__));

// Unzip selected zip file
if(isset($_POST['unzip'])){
 $filename = $_FILES['file']['name'];

 // Get file extension
 $ext = pathinfo($filename, PATHINFO_EXTENSION);

 $valid_ext = array('zip');

 // Check extension
 if(in_array(strtolower($ext),$valid_ext)){
  $tmp_name = $_FILES['file']['tmp_name'];

  $zip = new ZipArchive;
  $res = $zip->open($tmp_name);
  if ($res === TRUE) {

   // Unzip path
   $path = _PATH."/files/";

   // Extract file
   $zip->extractTo($path);
   $zip->close();

   echo 'Unzip!';
  } else {
   echo 'failed!';
  }
 }else{
  echo 'Invalid file';
 }
 
}
?>
<form method='post' action='' enctype='multipart/form-data'>
 
 <!-- Unzip selected zip file -->
 <input type='file' name='file'><br/>
 <input type='submit' name='unzip' value='Unzip' />
</form>