<?php
function MakeFriendlyUrl($sString)
{
    $asCharacters = Array(
       //WIN
        "\xb9" => "a", "\xa5" => "A", "\xe6" => "c", "\xc6" => "C",
        "\xea" => "e", "\xca" => "E", "\xb3" => "l", "\xa3" => "L",
        "\xf3" => "o", "\xd3" => "O", "\x9c" => "s", "\x8c" => "S",
        "\x9f" => "z", "\xaf" => "Z", "\xbf" => "z", "\xac" => "Z",
        "\xf1" => "n", "\xd1" => "N",
       //UTF
        "\xc4\x85" => "a", "\xc4\x84" => "A", "\xc4\x87" => "c", "\xc4\x86" => "C",
        "\xc4\x99" => "e", "\xc4\x98" => "E", "\xc5\x82" => "l", "\xc5\x81" => "L",
        "\xc3\xb3" => "o", "\xc3\x93" => "O", "\xc5\x9b" => "s", "\xc5\x9a" => "S",
        "\xc5\xbc" => "z", "\xc5\xbb" => "Z", "\xc5\xba" => "z", "\xc5\xb9" => "Z",
        "\xc5\x84" => "n", "\xc5\x83" => "N",
       //ISO
        "\xb1" => "a", "\xa1" => "A", "\xe6" => "c", "\xc6" => "C",
        "\xea" => "e", "\xca" => "E", "\xb3" => "l", "\xa3" => "L",
        "\xf3" => "o", "\xd3" => "O", "\xb6" => "s", "\xa6" => "S",
        "\xbc" => "z", "\xac" => "Z", "\xbf" => "z", "\xaf" => "Z",
        "\xf1" => "n", "\xd1" => "N");       
    $sString = strtr($sString, $asCharacters);
    $sString = strtr($sString, 'ĄŚŹąśź','ASZasz');
    $sString = preg_replace("'[[:punct:][:space:]]'",'-',$sString);
    $sString = strtolower($sString);
    $sChar = '-';
    $nRepeats = 1;
    $sString = preg_replace_callback('#(['.$sChar.'])\1{'.$nRepeats.',}#', create_function('$a', 'return substr($a[0], 0,'.$nRepeats.');'), $sString);
    return $sString;       
}  

$plik1=$_FILES['userfile']['name'];						
$nazwa_plik=$_FILES['userfile']['tmp_name'];
$file_extension = pathinfo($plik1, PATHINFO_EXTENSION);
$plik1= str_replace($file_extension,'',$plik1);
$plik1= substr($plik1,0,-1);
$plik1= MakeFriendlyUrl($plik1);
	$plik1 .= '.';
	$plik1 .= $file_extension;
	$i=1;		
				while(file_exists('./slides/'.$plik1))
{
$plik1= str_replace($file_extension,'',$plik1);
$plik1= substr($plik1,0,-1);
	$plik1 .= '-';
	$plik1 .= $i;
	$plik1 .= '.';
	$plik1 .= $file_extension;		
	$i++;	
    }

		
$uploaddir = './slides/'; //<--  Changed this to my directory for storing images
$uploadfile = $uploaddir . basename($plik1); //<-- IMPORTANT

function createThumbnail($filename) {

$final_width_of_image = 244;
$path_to_image_directory = './slides/';
$path_to_thumbs_directory = './thumbs/';

if(preg_match('/[.](jpg)$/', $filename)) {
    $im = imagecreatefromjpeg($path_to_image_directory . $filename);
} else if (preg_match('/[.](gif)$/', $filename)) {
    $im = imagecreatefromgif($path_to_image_directory . $filename);
} else if (preg_match('/[.](png)$/', $filename)) {
    $im = imagecreatefrompng($path_to_image_directory . $filename);
}

$ox = imagesx($im);
$oy = imagesy($im);
if($ox > $final_width_of_image ){

$nx = $final_width_of_image;
$ny = floor($oy * ($final_width_of_image / $ox));

$nm = imagecreatetruecolor($nx, $ny);

imagecopyresampled($nm, $im, 0,0,0,0,$nx,$ny,$ox,$oy);

if(!file_exists($path_to_thumbs_directory)) {
  if(!mkdir($path_to_thumbs_directory)) {
       die("There was a problem. Please try again!");
  } 
   }

imagejpeg($nm, $path_to_thumbs_directory . $filename);

}

} 

if (move_uploaded_file($nazwa_plik, $uploadfile)) {
			createThumbnail($plik1);   
		  echo "success:".$plik1;
		// IMPORTANT
} else {
  // WARNING! DO NOT USE "FALSE" STRING AS A RESPONSE!
  // Otherwise onSubmit event will not be fired
  echo "error";
}

?>