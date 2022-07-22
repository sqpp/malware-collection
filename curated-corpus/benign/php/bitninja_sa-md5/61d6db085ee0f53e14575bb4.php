<?php 

require_once '../../../wp-config.php';

function getHeight($image) {
	$sizes = getimagesize($image);
	$height = $sizes[1];
	return $height;
}

function getWidth($image) {
	$sizes = getimagesize($image);
	$width = $sizes[0];
	return $width;
}

function resizeImage($image,$width,$height,$scale) {
	$newImageWidth = ceil($width * $scale);
	$newImageHeight = ceil($height * $scale);
	$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);

	/*$ext = strtolower(substr(basename($image), strrpos(basename($image), ".") + 1));
	$source = "";
	if ($ext == "png") {
		$source = imagecreatefrompng($image);
	} elseif ($ext == "jpg" || $ext == "jpeg") {*/
		$source = imagecreatefromjpeg($image);
	/*} elseif ($ext == "gif") {
		$source = imagecreatefromgif($image);
	}*/

	imagecopyresampled($newImage,$source,0,0,0,0,$newImageWidth,$newImageHeight,$width,$height);
	imagejpeg($newImage,$image,82);
	chmod($image, 0777);

	imagedestroy($newImage);
	imagedestroy($source);
	return $image;
}

$userfile_name = $_FILES['image']['name'];
$userfile_tmp = $_FILES['image']['tmp_name'];
$userfile_size = $_FILES['image']['size'];
$filename = basename($_FILES['image']['name']);
$file_ext = substr($filename, strrpos($filename, '.') + 1);
$imgFilePath = $wppt_general_options['upload_path'].'/'.time().'_'.$userfile_name;

/* Only process if the file is a JPG and below the allowed limit */
if ((!empty($_FILES["image"])) && ($_FILES['image']['error'] == 0)) {
	if (($file_ext != "jpg") || ($userfile_size > $wppt_general_options['original_max_filesize'])) {
		$error = __('ONLY jpg images under 2MB are accepted for upload', 'wp-post-thumbnail');
	}
} else {
	$error= __('Select a .jpg image for upload', 'wp-post-thumbnail');
}

/* Everything is ok, so we can upload the image */
if (strlen($error)==0){
	if (isset($_FILES['image']['name'])){
		move_uploaded_file($userfile_tmp, $imgFilePath);
		chmod($imgFilePath, 0777);

		$width = getWidth($imgFilePath);
		$height = getHeight($imgFilePath);
		//Scale the image if it is greater than the width set above
		if ($width > $wppt_general_options['original_max_width']){
			$scale = $wppt_general_options['original_max_width']/$width;
			$uploaded = resizeImage($imgFilePath,$width,$height,$scale);
		}else{
			$scale = 1;
			$uploaded = resizeImage($imgFilePath,$width,$height,$scale);
		}

		// delete previous big image in cache
		$existing_original_img = $wppt_general_options['upload_path'].'/'.$wppt_general_options['original_file_name'];
		if (file_exists($existing_original_img)) {
			unlink($existing_original_img);
		}

		// update options with new big image filename
		$wppt_general_options['original_file_name'] = basename($imgFilePath);
		update_option('wppt_general_options', $wppt_general_options, 'WP-Post-Thumbnail general options');

	}
} else { ?>
	<script language="javascript" type="text/javascript">
		alert('<?php echo $error ?>');
	</script>

<?php } ?> <script language="javascript" type="text/javascript">window.top.window.EndUpload(1);</script>