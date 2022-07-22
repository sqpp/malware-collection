<?php
 session_start();
 include('verify_login.php');
 if(!verify_login()){ exit(); }

 include('timestamps.php');
 
function simple_munge($fname)
{
  $str = str_replace(array(' '), '_', $fname);
  return str_replace(array('*', '?', '&', ';'), '', $str);
}
  //MAIN ENTRY
header('Content-type: text/xml');
$target_path = "../sg_userfiles/";

if(!file_exists($target_path)){ mkdir($target_path);  }

$bname = basename( simple_munge( $_FILES['Filedata']['name'])); 
$target_path = $target_path . $bname;

if(move_uploaded_file($_FILES['Filedata']['tmp_name'], $target_path)) {
  log_timestamp('sg_userfiles/' . $bname, ':new',   'cms');
  log_timestamp('sg_userfiles',           ':mkdir', 'cms');
  echo "<ok />";
} else{
  trigger_error( "There was an error uploading the file", E_USER_NOTICE);
  echo "<error />";
}


?>