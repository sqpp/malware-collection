<?php
// Get the session Id passed from SWFUpload. We have to do this to work-around the Flash Player Cookie Bug
if (isset($_POST['PHPSESSID']))
{
	session_id($_POST['PHPSESSID']);
}

session_start();
ini_set("html_errors", "0");

// Check the upload
if (!isset($_FILES['Filedata']) || !is_uploaded_file($_FILES['Filedata']['tmp_name']) || $_FILES['Filedata']['error'] != 0)
{
	echo 'ERROR:invalid upload';
	exit(0);
}

// Check the uploaddirectory
if ( !isset($_SESSION['uploaddir']) || !is_dir($_SESSION['rootpath'] . $_SESSION['uploaddir']) )
{
	echo 'ERROR:invalid uploaddirectory';
	exit(0);
}

function _CreateThumbnail($thumbname, $image, $thumbwidth, $thumbheight)
{
	$imgdata = @getimagesize($image);
	if ( $imgdata === FALSE ) return FALSE;
	$imgratio = $imgdata[0] / $imgdata[1];  // width/height
	$thumbratio = $thumbwidth / $thumbheight;
	$src_x = 0;
	$src_y = 0;
	$src_w = $imgdata[0];
	$src_h = $imgdata[1];
	if( $imgratio > $thumbratio )
	{
		$newwidth = $thumbwidth;
		$newheight = ceil($thumbwidth / $imgratio);
	}
	else
	{
		$newheight = $thumbheight;
		$newwidth = ceil($thumbheight * $imgratio);
	}
	$newimage = imagecreatetruecolor($newwidth, $newheight);
	switch($imgdata[2]) {
		case IMAGETYPE_GIF:
			$source = imagecreatefromgif($image);
			$trnprt_indx = imagecolortransparent($source);
			if ($trnprt_indx >= 0)
			{
			@$trnprt_color = imagecolorsforindex($source, $trnprt_indx);
			$trnprt_indx = imagecolorallocate($newimage, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
			imagefill($newimage, 0, 0, $trnprt_indx);
			imagecolortransparent($newimage, $trnprt_indx);
			@imagetruecolortopalette($newimage, true, imagecolorstotal($image));
			}
			break;
		case IMAGETYPE_JPEG:
			$source = imagecreatefromjpeg($image);
			break;
		case IMAGETYPE_PNG:
			$source = imagecreatefrompng($image);
			imagealphablending($newimage, false);
			$trnprt_color = imagecolorallocatealpha($newimage, 0, 0, 0, 127);
			imagefill($newimage, 0, 0, $trnprt_color);
			imagesavealpha($newimage, true);
			break;
		default:
			return FALSE;
	}

	imagecopyresampled($newimage, $source, 0, 0, $src_x, $src_y, $newwidth, $newheight, $src_w, $src_h);
	switch($imgdata[2]) {
		case IMAGETYPE_GIF:
			imagegif($newimage, $thumbname);
			break;
		case IMAGETYPE_JPEG:
			imagejpeg($newimage, $thumbname, 80);
			break;
		case IMAGETYPE_PNG:
			imagepng($newimage, $thumbname);
			break;
		default:
			return FALSE;
	}
	imagedestroy($newimage);
	return TRUE;
}

if (!isset($_SESSION['file_info']))
{
	$_SESSION['file_info'] = array();
}

// cleanup the filename, copied some code from munge_string_to_url() and modified to exclude the extension
$pos = strrpos($_FILES['Filedata']['name'], '.');
include('../../lib/replacement.php');
$alias = substr($_FILES['Filedata']['name'], 0, $pos);
$alias = str_replace($toreplace, $replacement, $alias);
$alias = preg_replace('/[^a-z0-9-_]+/i','-',$alias);
$alias = trim($alias . substr($_FILES['Filedata']['name'], $pos), '-');

$file_id = md5(rand()*10000000);
$filename = $_SESSION['uploaddir'] . '/' . $alias;
$thumbname = $_SESSION['uploaddir'] . '/thumb_' . $alias;
$_SESSION['file_info'][$file_id] = '../../' . $thumbname;

if ( _CreateThumbnail('../../' . $thumbname, $_FILES['Filedata']['tmp_name'], 96, 96) )
{
	move_uploaded_file($_FILES['Filedata']['tmp_name'], str_replace('/', DIRECTORY_SEPARATOR, $_SESSION['rootpath'] . $filename));
	echo 'FILEID:' . $file_id;	// Return the file id to the script
}
else
{
	echo 'File corrupt: ' . $alias;
}
?>