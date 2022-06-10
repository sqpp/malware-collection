<?php
$uploaddir = "../../../uploads/wpsc/product_images/";
$file = $uploaddir . basename($_FILES['uploadfile']['name']); 
$type = $_FILES["uploadfile"]["type"];
$size=$_FILES['uploadfile']['size'];

if($size>1048576)
{
	echo "error file size > 1 MB";
	unlink($_FILES['uploadfile']['tmp_name']);
	exit;
}
$newfile = "../../../uploads/wpsc/product_images/thumbnails/" . basename($_FILES['uploadfile']['name']); 

if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $file)) { 
	if(!copy($file, $newfile)){
		echo "failed to copy $newfile...\n";
	}
	echo " image[ok] $newfile"; 
} else {
	echo " error ".$_FILES['uploadfile']['error']." --- ".$_FILES['uploadfile']['tmp_name']." %%% ".$file."($size)";
}


?>