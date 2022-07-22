<?php

error_reporting(0);
if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {

    $path = "uploads/"; //set your folder path

    $ext = pathinfo($_FILES['video_local']['name'], PATHINFO_EXTENSION);

    $video_local=date('dmYhis').'_'.rand(0,99999).".".$ext;

    $tmp = $_FILES['video_local']['tmp_name'];
    
    if (move_uploaded_file($tmp, $path.$video_local)) 
    { //check if it the file move successfully.
        echo $video_local;
    } else {
        echo "failed";
    }
    exit;
}