<?php

include("../../../../wp-config.php");

/* To off  display error or warning which is set of in wp-confing file --- 
  // use this lines after including wp-config.php file
 */
error_reporting(0);
@ini_set('display_errors', 0);
error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));

/* ------------- end of show error off code------------------------------------------ */

include_once("logs.php");
log_init();

log_message('debug', 'callupload');

$user_id = 885;

if ($_FILES["ReferenceName"]["error"] > 0) {
    log_message('debug', 'erroe' . $_FILES["ReferenceName"]["error"]);
    echo "Error: " . $_FILES["ReferenceName"]["error"] . "<br>";
} else {
    echo "Upload: " . $_FILES["ReferenceName"]["name"] . "<br>";
    echo "Type: " . $_FILES["ReferenceName"]["type"] . "<br>";
    echo "Size: " . ($_FILES["ReferenceName"]["size"] / 1024) . " kB<br>";
    echo "Stored in: " . $_FILES["ReferenceName"]["tmp_name"] . "<br>";

    $target = $_FILES['ReferenceName']['tmp_name'];
    $destination = 'upload/' . $_FILES["ReferenceName"]["name"];

    log_message('debug', 'upload name' . $destination);
    //  echo "success";
    $img = $_FILES["ReferenceName"]["name"];
    //   $img_name =basename($img);				

    $new_name = "1_" . $img;

    $uploadfile = ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/" . $new_name;

    log_message('debug', 'upload-path' . $uploadfile);

    if (move_uploaded_file($target, $uploadfile)) {
        log_message('debug', 'upload success');
        echo "success";
    }
}
?>