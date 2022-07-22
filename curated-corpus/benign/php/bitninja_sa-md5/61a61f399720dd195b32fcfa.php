<?php
error_reporting(0);
header('Content-Type: text/plain; charset=utf-8');

require_once('../../../../../../wp-config.php');
require_once(ABSPATH . 'wp-settings.php');

$allowed = array('aaaa','png','doc','docx','xls','xlsx','csv','txt','rtf','zip','mp3','wma','wmv','mpg','flv','avi','jpg','jpeg','png','gif','ods','rar','ppt','tif','wav','mov','psd','eps','sit','sitx','cdr','ai','mp4','m4a','bmp','pps','aif','pdf');


if ( !isset($_FILES) ) { echo json_encode(array('failed'=>'No file found')); die(); }
if ( !isset($_FILES['files']) ) { echo json_encode(array('failed'=>'No file found 2')); die(); }
if ( !is_writable(getcwd().'/files/') ) { echo json_encode(array('failed'=>'Not writable')); die(); }

if (function_exists('is_multisite') && is_multisite())
{
	$base = getcwd().'/files/'.$wpdb->blogid.'/';
	$full = plugins_url('formcraft/file-upload/server/content/files/'.$wpdb->blogid.'/');
	mkdir($base, 0755);
}
else
{
	$base = getcwd().'/files/';
	$full = plugins_url('formcraft/file-upload/server/content/files/');	
}

foreach ($_FILES as $key => $file)
{
	$extension = strtolower(end(explode('.', $file['name'][0])));
	$test = explode('.', $file['name'][0]);
	$count = count($test)-1;
	unset($test[$count]);
	$file_name = implode('', $test).'.'.$extension;
	if(!in_array($extension, $allowed)) {echo json_encode(array('failed'=>'Not allowed')); die();}
	$uniq = uniqid(1);
	$new_name = $uniq.'---'.$file_name;
	$uploads_dir = $base.$new_name;
	$moved = move_uploaded_file($file['tmp_name'][0], $uploads_dir);
	$files['files']['name'] = $file_name;
	$files['files']['new_name'] = $new_name;
	$files['files']['size'] = $file['size'][0];
	$files['files']['url'] = $uploads_dir;
	$files['files']['full-url'] = $full.$new_name;
	$files['files']['fullurl'] = $full.$new_name;
	$file = $base.'info.txt';
	$old = file_get_contents($file);
	if (!$old)
	{
		$new = array();
	}
	else
	{
		$new = json_decode($old, 1);
	}
	$new[$uniq] = json_encode($files['files']);
	file_put_contents($file, json_encode($new), LOCK_EX);
}

echo json_encode($files);


?>