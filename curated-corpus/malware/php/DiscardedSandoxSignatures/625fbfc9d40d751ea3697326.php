<?php 
preg_match('|^(.*?/)(wp-content)/|i', str_replace('\\', '/', __FILE__), $_m);
require_once $_m[1].'wp-load.php';


if ( is_ssl() && empty($_COOKIE[SECURE_AUTH_COOKIE]) && !empty($_REQUEST['auth_cookie']) )
	$_COOKIE[SECURE_AUTH_COOKIE] = $_REQUEST['auth_cookie'];
elseif ( empty($_COOKIE[AUTH_COOKIE]) && !empty($_REQUEST['auth_cookie']) )
	$_COOKIE[AUTH_COOKIE] = $_REQUEST['auth_cookie'];
if ( empty($_COOKIE[LOGGED_IN_COOKIE]) && !empty($_REQUEST['logged_in_cookie']) )
	$_COOKIE[LOGGED_IN_COOKIE] = $_REQUEST['logged_in_cookie'];
unset($current_user);



if ( !current_user_can('upload_files') )
	die(__('You do not have permission to upload files.'));



$POST_MAX_SIZE = ini_get('post_max_size');
$unit = strtoupper(substr($POST_MAX_SIZE, -1));
$multiplier = $unit == 'M' ? 1048576 : ($unit == 'K' ? 1024 : ($unit == 'G' ? 1073741824 : 1));

if ((int)$_SERVER['CONTENT_LENGTH'] > $multiplier*(int)$POST_MAX_SIZE && $POST_MAX_SIZE) {
	
	echo "POST exceeded maximum allowed size.";
	exit(0);
}


$save_path = empty($_GET['dir']) ? '../../pageflip/upload/' : $_GET['dir'];		
$upload_name = "Filedata";
$max_file_size_in_bytes = 2147483647;	
$extension_whitelist = array('gif', 'jpg', 'jpeg', 'png', 'swf');	
$valid_chars_regex = '.A-Z0-9_ !@#$%^&()+={}\[\]\',~`-';			


$MAX_FILENAME_LENGTH = 260;
$file_name = "";
$file_extension = "";
$uploadErrors = array(
	0=>"There is no error, the file uploaded with success.",
	1=>"The uploaded file exceeds the upload_max_filesize directive in php.ini",
	2=>"The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.",
	3=>"The uploaded file was only partially uploaded.",
	4=>"No file was uploaded.",
	6=>"Missing a temporary folder."
);



if (!isset($_FILES[$upload_name])) {
	HandleError("No upload found in \$_FILES for " . $upload_name);
	exit(0);
} else if (isset($_FILES[$upload_name]["error"]) && $_FILES[$upload_name]["error"] != 0) {
	HandleError($uploadErrors[$_FILES[$upload_name]["error"]]);
	exit(0);
} else if (!isset($_FILES[$upload_name]["tmp_name"]) || !@is_uploaded_file($_FILES[$upload_name]["tmp_name"])) {
	HandleError("Upload failed is_uploaded_file test.");
	exit(0);
} else if (!isset($_FILES[$upload_name]['name'])) {
	HandleError("File has no name.");
	exit(0);
}


$file_size = @filesize($_FILES[$upload_name]["tmp_name"]);
if (!$file_size || $file_size > $max_file_size_in_bytes) {
	HandleError("File exceeds the maximum allowed size.");
	exit(0);
}

if ($file_size <= 0) {
	HandleError("File size outside allowed lower bound.");
	exit(0);
}



$file_name = preg_replace('/[^'.$valid_chars_regex.']|\.+$/i', "", basename($_FILES[$upload_name]['name']));
if (strlen($file_name) == 0 || strlen($file_name) > $MAX_FILENAME_LENGTH) {
	HandleError("Invalid file name");
	exit(0);
}

if (file_exists($save_path.$file_name)) {
	
	unlink($save_path.$file_name);
}


$path_info = pathinfo($_FILES[$upload_name]['name']);
$file_extension = $path_info["extension"];
$is_valid_extension = false;
foreach ($extension_whitelist as $extension) {
	if (strcasecmp($file_extension, $extension) == 0) {
		$is_valid_extension = true;
		break;
	}
}
if (!$is_valid_extension) {
	HandleError("Invalid file extension.");
	exit(0);
}







if (@move_uploaded_file($_FILES[$upload_name]["tmp_name"], $save_path.$file_name)) {
	@chmod($save_path.$file_name, 0777);
}
else {
	HandleError("File could not be saved.");
	exit(0);
}

exit(0);



function HandleError($message) {
	echo $message;
}


?>