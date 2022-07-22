<?php

require_once(dirname(__FILE__).'/fpd-image-utils.php');

$valid_mime_types = array(
    "image/png",
    "image/jpeg",
    "image/pjpeg",
    "image/svg+xml",
    "application/pdf"
);

$uploads_dir = $_POST['uploadsDir'];
$uploads_dir_url = $_POST['uploadsDirURL'];
$save_on_server = isset($_POST['saveOnServer']) ? (int) $_POST['saveOnServer'] : false;

if(empty($uploads_dir) || empty($uploads_dir_url)) {
	die( json_encode(array('error' => 'You need to define a directory, where you want to save the uploaded user images!')) );
}

if(!function_exists('getimagesize')) {
	die( json_encode(array('error' => 'The php function getimagesize is not installed on your server. Please contact your server provider!')) );
}

if(isset($_FILES) && sizeof($_FILES) > 0 && isset($_FILES['pdf'])) {

	if( !extension_loaded('imagick') )
		die( json_encode(array('error' => 'Imagick extension is required in order to upload PDF files. Please enable Imagick on your server!')) );

	$pdf_file = $_FILES['pdf'];

	// First things first: input sanitation and security checks
	try {
		$sanitized_name = FPD_Image_Utils::sanitize_filename($pdf_file['name']);
	}
	catch (Exception $e) {
		die(json_encode(array('error' => $e->getMessage())));
	}

	$parts = pathinfo($sanitized_name);
	$filename = $parts['filename'];
	$ext = strtolower($parts['extension']);

	//check for php errors
	if( isset($file['error']) && $file['error'] !== UPLOAD_ERR_OK ) {
		die( json_encode( array(
			'error' => FPD_Image_Utils::file_upload_error_message($file['error']),
			'filename' => $filename
		)) );
	}

	if( !in_array($pdf_file['type'], $valid_mime_types) ) {
		die( json_encode(array(
			'error' => 'This file is not a PDF!',
			'filename' => $filename
		)) );
	}

	$upload_path = FPD_Image_Utils::get_upload_path($uploads_dir, $filename, $ext);
	$pdf_path = $upload_path['full_path'].'.'.$ext;
	$pdf_url = $uploads_dir_url.'/'.$upload_path['date_path'].'.'.$ext;

	$pdf_images = array();

	if( @move_uploaded_file($pdf_file['tmp_name'], $pdf_path) ) {

		$im = new Imagick();
		$im->setBackgroundColor(new ImagickPixel('transparent'));
		$im->readImage($pdf_path);

		for($i = 0;$i < $im->getNumberImages(); $i++) {

			$image_name = $filename.'_'.($i+1) . '.png';
			$upload_path = FPD_Image_Utils::get_upload_path($uploads_dir, $image_name, 'png');
			$temp_image_path = $upload_path['full_path'];

			$im->setIteratorIndex($i);
			$im->setImageResolution(300, 300);
			$im->setImageUnits(imagick::RESOLUTION_PIXELSPERINCH);
			$im->setImageFormat('png32');
			$im->writeImage($temp_image_path);

			$pdf_images[] = array(
				'filename' => $image_name,
				'image_url' => $uploads_dir_url.$upload_path['date_path'],
			);

		}

		echo json_encode( array(
			'pdf_images' => $pdf_images,
		) );

		$im->destroy();

	}
	else {

		echo json_encode( array(
			'error' => 'PHP Issue - move_upload_file failed.',
			'filename' => $filename
		) );

	}

	die;
}

//upload image
if(isset($_FILES) && sizeof($_FILES) > 0) {

	$warning = null;

	foreach($_FILES as $fieldName => $file) {

		// First things first: input sanitation and security checks
		try {
			$sanitized_name = FPD_Image_Utils::sanitize_filename($file['name'][0]);
		}
		catch (Exception $e) {
			die(json_encode(array('error' => $e->getMessage())));
		}

		// Determining file name parts using pathinfo() instead of explode()
		// prevents double extensions (file.jpg.php) and directory traversal (../../file.jpg)
		$parts = pathinfo($sanitized_name);
		$filename = $parts['filename'];
		$ext = strtolower($parts['extension']);

		//check for php errors
		if( isset($file['error']) && $file['error'][0] !== UPLOAD_ERR_OK ) {
			die( json_encode( array(
				'error' => FPD_Image_Utils::file_upload_error_message($file['error'][0]),
				'filename' => $filename
			)) );
		}

		//check if its an image
		if( (!getimagesize($file['tmp_name'][0]) && $ext !== 'svg') || !in_array($file['type'][0], $valid_mime_types) ) {
			die( json_encode(array(
				'error' => 'This file is not an image!',
				'filename' => $filename
			)) );
		}

		$upload_path = FPD_Image_Utils::get_upload_path($uploads_dir, $filename, $ext);
		$image_path = $upload_path['full_path'].'.'.$ext;
		$image_url = $uploads_dir_url.'/'.$upload_path['date_path'].'.'.$ext;

		if( @move_uploaded_file($file['tmp_name'][0], $image_path) ) {

			if($ext === 'jpg' || $ext === 'jpeg') {

				if(  function_exists('exif_read_data') ) {
					$exif = @exif_read_data($image_path);
				    if ($exif && isset($exif['Orientation']) && !empty($exif['Orientation'])) {

				        $image = imagecreatefromjpeg($image_path);
				        unlink($image_path);
				        switch ($exif['Orientation']) {
				            case 3:
				                $image = imagerotate($image, 180, 0);
				                break;

				            case 6:
				                $image = imagerotate($image, -90, 0);
				                break;

				            case 8:
				                $image = imagerotate($image, 90, 0);
				                break;
				        }

				        imagejpeg($image, $image_path, 90);
				    }
				}
				else
					$warning = 'exif_read_data function is not enabled.';

			}

			echo json_encode( array(
				'image_src' => $image_url,
				'filename' => $filename,
				'warning' => $warning
			) );

		}
		else {

			echo json_encode( array(
				'error' => 'PHP Issue - move_upload_file failed.',
				'filename' => $filename
			) );

		}

	}

	die;

}

$url = $_POST['url'];
$mime_type = FPD_Image_Utils::is_image($url);
if ( $mime_type === false ) {
	$last_error = error_get_last();
	die( json_encode(array('error' => is_array($last_error) ?  $last_error['message'] : 'File is not an image!')) );
}

$ext = str_replace('image/', '', $mime_type);

if($save_on_server) {

	$unique_name = @date() === false ? md5(gmdate('Y-m-d H:i:s:u')) : md5(date('Y-m-d H:i:s:u')); //create an unique name
	$upload_path = FPD_Image_Utils::get_upload_path($uploads_dir, $unique_name);
	$image_path = $upload_path['full_path'].'.'.$ext;
	$image_url = $uploads_dir_url.'/'.$upload_path['date_path'].'.'.$ext;

}

//use curl
$result = false;
if( function_exists('curl_exec') ) {

	try {

		////create image on server from url
		if($save_on_server) {

			$ch = curl_init($url);
			$fp = fopen($image_path, 'wb');
			curl_setopt($ch, CURLOPT_FILE, $fp);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$result = curl_exec($ch);
			curl_close($ch);
			fclose($fp);

		}
		//get data uri from url
		else {

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
			$result = curl_exec($ch);
			curl_close($ch);

			$info = getimagesize($url);
			$image_url = 'data: '.$info['mime'].';base64,'.base64_encode($result);

		}

	}
	catch(Exception $e) {

	}

}

//curl not working, try other functions
if($result === false) {

	//create image on server from data uri
	if($save_on_server) {
		file_put_contents($image_path, file_get_contents($url));
		$result = file_get_contents($url);
	}
	//get data uri from url
	else {
		$result = file_get_contents($url);
		$info = getimagesize($url);
		$image_url = 'data: '.$info['mime'].';base64,'.base64_encode($result);
	}

}

if($result) {
	echo json_encode(array( 'image_src' => $image_url));
}
else {
	echo json_encode(array('error' => 'The image could not be created. Please view the error log file of your server to see what went wrong!'));
}

?>