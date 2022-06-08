<?php
include('start_session.php');

// Set Remote CM Login Vars
$cm_login = FALSE;
if(file_exists('verify_login.php')) {
  include('verify_login.php');
  $cm_login = verify_login();
}

// Set Blog Login Vars
$blog_login = FALSE;
if(file_exists('blog_engine/base.php') && file_exists('blog_engine/xml_object.php') && file_exists('blog_engine/libraries.php')) {
  include('blog_engine/base.php');
  include('blog_engine/xml_object.php');
  include('blog_engine/libraries.php');
  $auth = new sg_auth(array('path_to_root' => '../'));
  $blog_login = $auth->check_login();
}

// Set Simple Blog Login Vars
$simple_blog_login = FALSE;
if(isset($_SESSION['simpleblog']['blog_login_script']) && file_exists($_SESSION['simpleblog']['blog_login_script'])) {
  include($_SESSION['simpleblog']['blog_login_script']);
  $simple_blog_login = verify_blog_login(FALSE);
}

if($cm_login === FALSE && $blog_login === FALSE && $simple_blog_login === FALSE) {
  die('login failure');
}

include('timestamps.php');

$result = 0;

function simple_munge($fname)
{
  $str = str_replace(array(' '), '_', $fname);
  return str_replace(array('*', '?', '&', ';'), '', $str);
}
  //MAIN ENTRY
//header('Content-type: text/xml');
$callback = $_POST['SG_File_Callback'];
$target_path = "../sg_userfiles/";

if(!file_exists($target_path)){ mkdir($target_path);  }

$bname = basename( simple_munge( $_FILES['Filedata']['name']));
$target_path = $target_path . $bname;
if(move_uploaded_file($_FILES['Filedata']['tmp_name'], $target_path)) {
  log_timestamp('sg_userfiles/' . $bname, ':new',   'cms');
  log_timestamp('sg_userfiles',           ':mkdir', 'cms');
  $result = 1;
} else{
  trigger_error( "There was an error uploading the file", E_USER_NOTICE);
}

?>

<script type="text/javascript">var sg_sulf = <?php echo $callback; ?>;if(typeof sg_sulf == 'function'){sg_sulf(<?php echo $result . ",\"" . $bname . "\""; ?>);}</script>