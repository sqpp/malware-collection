<?php
$outp = '';

//print_r($_POST);

$request_body = file_get_contents('php://input');

//$outp = str_replace("'", '', $outp);
//$outp = trim(preg_replace('/\s+/', '', $outp));
//echo $outp;

//echo ;

if ( isset ( $request_body ) && $request_body) {


    $fileName = 'img.png';
    if(isset($_GET['location'])){
        $fileName = $_GET['location'];
        $fileName = str_replace('{{dirname}}', dirname(__FILE__) . '/', $fileName);
    }

//the image file name

// get the binary stream
    $im = $request_body;

//write it
    $fp = fopen($fileName, 'wb');
    fwrite($fp, $im);
    fclose($fp);

//echo the fileName;
    echo $fileName;


} else {
    echo "error:http raw post data does not exist";
}